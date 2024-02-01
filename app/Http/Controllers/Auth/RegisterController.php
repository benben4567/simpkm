<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Services\GetStudentService;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = 'login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nim' => ['required', 'unique:students'],
            'angkatan' => ['required'],
        ]);
    }

    protected function create(array $data)
    {
        $angkatan = $data['angkatan'];
        $nim = $data['nim'];
        
        $response = GetStudentService::checkNim($angkatan, $nim);
        
        if ($response == false) {
            return redirect()->back()->with('error', 'Tidak dapat mengakses Siakad. Silahkan coba lagi nanti.');
        }
        
        if ($response['data'] == null || $response['data'] == '') {
            return redirect()->back()->with('error', 'NIM tidak ditemukan. Pastikan Tahun Angkatan dan NIM benar.');
        }
        
        $prodi = Major::where('kode_prodi', $response['data']['kode_prodi'])->first();
        
        $data = [
            'nim' => $response['data']['nim'],
            'nama' => $response['data']['nama_mahasiswa'],
            'prodi' => $prodi->kode_prodi . ' - ' . $prodi->jenjang . ' ' . $prodi->nama,
            'telepon' => $response['data']['telepon'],
            'email' => $response['data']['email'],
        ];
        
        $user = User::create([
            'email' => $response['data']['email'],
            'username' => $response['data']['nim'],
            'name' => ucwords(trim($response['data']['nama_mahasiswa'])),
            'password' => $response['data']['password'] == '' ? Hash::make($response['data']['nim']) : $response['data']['password'],
            'no_hp' => $response['data']['telepon'],
            'email_verified_at' => now(),
        ]);

        $user->assignRole('student');

        $user->student()->create([
            'major_id' => $prodi->id,
            'nim' => $response['data']['nim'],
            'nama' => ucwords(trim($response['data']['nama_mahasiswa'])),
            'jk' => $response['data']['jenis_kelamin'] == 'L' ? 'laki' : 'perempuan',
            'tempat_lahir' => $response['data']['tempat_lahir'],
            'tgl_lahir' => $response['data']['tanggal_lahir'],
            'no_hp' => $response['data']['telepon'],
        ]);

        return $user;
    }

    public function showRegistrationForm()
    {
        return view('auth.register2');
    }

    public function checkNim(Request $request)
    {
        try {
            $angkatan = $request->input('angkatan');
            $nim = $request->input('nim');
            
            // check if NIM exist in database
            $student = User::where('username', $nim)->first();
            
            if ($student) {
                return ResponseFormatter::error(null, 'NIM sudah pernah terdaftar. Silahkan login untuk masuk ke sistem.', 409);
            }

            $response = GetStudentService::checkNim($angkatan, $nim);
            
            if ($response == false) {
                return ResponseFormatter::error(null, 'Tidak dapat mengakses Siakad. Silahkan coba lagi nanti.', 500);
            }

            if ($response['data'] == null || $response['data'] == '') {
                return ResponseFormatter::error(null, 'Data tidak ditemukan. Pastikan Tahun Angkatan dan NIM sudah benar.', 404);
            }
            
            $prodi = Major::where('kode_prodi', $response['data']['kode_prodi'])->first();
            
            $data = [
                'nim' => $response['data']['nim'],
                'nama' => $response['data']['nama_mahasiswa'],
                'prodi' => $prodi->kode_prodi . ' - ' . $prodi->jenjang . ' ' . $prodi->nama,
                'telepon' => $response['data']['telepon'],
                'email' => $response['data']['email'],
            ];
            
            return ResponseFormatter::success($data, 'Data ditemukan');
        } catch (\Exception $e) {
            Log::error("RegisterController@checkNim: {$e->getMessage()}");
            return ResponseFormatter::error(null, 'Gagal cek data mahasiswa. Silahkan coba lagi nanti.', 500);
        }
    }
}
