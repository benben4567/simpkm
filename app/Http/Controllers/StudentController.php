<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Major;
use App\Models\Student;
use App\Models\User;
use App\Services\GetStudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nim' => 'required|numeric|unique:students,nim',
                'nama' => 'required|string',
                'major' => 'required|exists:majors,id',
                'tempat' => 'required|string',
                'tgl' => 'required|date',
                'jk' => 'required|string',
                'email' => 'required|email',
                'no_hp' => 'required|digits_between:10,13',
                'password' => 'nullable',
            ]);
            
            if ($validator->fails()) {
                return ResponseFormatter::error($validator->errors(), "Data yang diberikan tidak valid. Silahkan coba lagi.", 422);
            }
            
            $student = User::create([
                'email' => $request->input('email'),
                'username' => $request->input('nim'),
                'name' => trim($request->input('nama')),
                'password' => $request->input('password') == '' ? Hash::make($request->input('nim')) : $request->input('password'),
                'no_hp' => $request->input('no_hp'),
                'email_verified_at' => now(),
            ]);

            $student->assignRole('student');

            $student->student()->create([
                'major_id' => $request->input('major'),
                'nim' => $request->input('nim'),
                'nama' => trim($request->input('nama')),
                'jk' => $request->input('jk'),
                'tempat_lahir' => $request->input('tempat'),
                'tgl_lahir' => $request->input('tgl'),
                'no_hp' => $request->input('no_hp'),
            ]);
            
            return ResponseFormatter::success(null, 'Data mahasiswa berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error("StudentController@store: {$e->getMessage()}");
            return ResponseFormatter::error(null, 'Tidak dapat mengakses Siakad. Kesalahan Server', 500);
        }
    }
    
    public function update(Request $request)
    {
        try {
            $id = $request->input('id');
            $user = User::find($id);
            
            if ($user == null) {
                return ResponseFormatter::error(null, 'Data mahasiswa tidak ditemukan.', 404);
            }
            
            $student = $user->student;
            
            $validator = Validator::make($request->all(), [
                'nim' => 'required|numeric|unique:students,nim,' . $student->id,
                'name' => 'required|string',
                'major' => 'required|exists:majors,id',
                'tempat' => 'required|string',
                'tgl' => 'required|date',
                'jk' => 'required|string',
                'email' => 'required|email',
                'no_hp' => 'required|digits_between:10,13',
            ]);
            
            if ($validator->fails()) {
                return ResponseFormatter::error($validator->errors(), "Data yang diberikan tidak valid. Silahkan coba lagi.", 422);
            }
            
            $student->update([
                'major_id' => $request->input('major'),
                'nim' => $request->input('nim'),
                'nama' => trim($request->input('name')),
                'jk' => $request->input('jk'),
                'tempat_lahir' => $request->input('tempat'),
                'tgl_lahir' => $request->input('tgl'),
                'no_hp' => $request->input('no_hp'),
            ]);
            
            $user = $student->user()->update([
                'email' => $request->input('email'),
                'name' => trim($request->input('name')),
                'username' => $request->input('nim'),
                'no_hp' => $request->input('no_hp'),
            ]);
            
            return ResponseFormatter::success(null, 'Data mahasiswa berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("StudentController@update: {$e->getMessage()}");
            return ResponseFormatter::error(null, 'Data mahasiswa gagal diperbarui. Kesalahan Server.', 500);
        }
    }
    
    public function resetPassword(Request $request)
    {
        try {
            $user = User::find($request->id);
            
            if ($user == null) {
                return ResponseFormatter::error(null, 'Data mahasiswa tidak ditemukan.', 404);
            }
            
            $student = $user->student;
            $user->update([
                'password' => Hash::make($student->nim),
            ]);
            
            return ResponseFormatter::success(null, 'Password mahasiswa berhasil direset.');
        } catch (\Exception $e) {
            Log::error("StudentController@resetPassword: {$e->getMessage()}");
            return ResponseFormatter::error(null, 'Password mahasiswa gagal direset. Kesalahan Server.', 500);
        }
    }
    
    public function toggle(Request $request)
    {
        try {
            $user = User::find($request->id);
            if ($user == null) {
                return ResponseFormatter::error(null, 'Data mahasiswa tidak ditemukan.', 404);
            }
            
            $user->status = $user->status == 'aktif' ? 'nonaktif' : 'aktif';
            $user->save();
            
            return ResponseFormatter::success(null, 'Status mahasiswa berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("StudentController@toggle: {$e->getMessage()}");
            return ResponseFormatter::error(null, 'Status mahasiswa gagal diperbarui. Kesalahan Server.', 500);
        }
    }
    
    public function checkNim(Request $request)
    {
        $angkatan = $request->input('angkatan');
        $nim = $request->input('nim');
        
        $response = GetStudentService::checkNim($angkatan, $nim);
        
        if ($response == false) {
            return ResponseFormatter::error(null, 'Tidak dapat mengakses Siakad. Kesalahan Server', 500);
        }
        
        if($response['data'] == null)
        {
            return ResponseFormatter::error(null, 'NIM tidak ditemukan', 404);
        }
        
        $data = [
            'nim' => $response['data']['nim'],
            'nama' => $response['data']['nama_mahasiswa'],
            'prodi' => Major::where('kode_prodi', $response['data']['kode_prodi'])->first()->id,
            'jk' => $response['data']['jenis_kelamin'] == 'L' ? 'laki' : 'perempuan',
            'tempat_lahir' => $response['data']['tempat_lahir'],
            'tanggal_lahir' => $response['data']['tanggal_lahir'],
            'telepon' => $response['data']['telepon'],
            'email' => $response['data']['email'],
            'password' => $response['data']['password'],
        ];
        
        return ResponseFormatter::success($data, 'NIM ditemukan');
    }
}
