<?php

namespace App\Http\Controllers;

use App\Major;
use App\Teacher;
use App\Student;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Input\Input;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index()
    {
      $admins = User::whereRole('admin')->get();
      // $students = User::with('student')->where('role', 'student')->get();
      $students = Student::all();
      $teachers = User::whereRole('teacher')->get();
      return view('pages.admin.user', compact('admins', 'students', 'teachers'));
    }

    public function create($role)
    {
      $majors = Major::all();
      switch ($role) {
        case 'student':
          return view('pages.admin.user_student', compact('majors') );
          break;
        case 'teacher':
          return view('pages.admin.user_teacher', compact('majors'));
          break;
        default:
          return abort('404');
          break;
      }
    }

    public function store(Request $request, $role)
    {
      $rules = [
        'email' => 'required|unique:users,email',
        'name' => 'required',
        'password' => 'required|min:8|confirmed'
      ];

      if ($role == 'student') {
        $rules['nim'] = 'required|unique:students,nim';
        $rules['major'] = 'required';
        $rules['tempat'] = 'required';
        $rules['tgl'] = 'required|date_format:Y-m-d';
        $rules['jk'] = 'required';
        $rules['no_hp'] = 'required|digits_between:11,13';
        $this->validate($request,$rules);
      } elseif ($role == 'teacher') {
        $rules['nidn'] = 'required|unique:teachers,nidn';
        $rules['major'] = 'required';
        $rules['tempat'] = 'required';
        $rules['tgl'] = 'required|date_format:Y-m-d';
        $rules['jk'] = 'required';
        $rules['no_hp'] = 'required|digits_between:11,13';
        $this->validate($request,$rules);
      } else {
        $this->validate($request,$rules);
      }

      $request->merge(['role' => $role]);
      $request->merge(['password' => Hash::make($request->input('password'))]);

      try {
        DB::transaction(function () use ($request, $role) {
          $user = User::create($request->all());
          if ($role == 'student') {
            $user->student()->create([
              'nim' => $request->input('nim'),
              'major_id' => $request->input('major'),
              'nama' => $request->input('name'),
              'tempat_lahir' => $request->input('tempat'),
              'tgl_lahir' => $request->input('tgl'),
              'no_hp' => $request->input('no_hp'),
              'jk' => $request->input('jk')
            ]);
          } elseif ($role == 'teacher') {
            $user->teacher()->create([
              'nidn' => $request->input('nidn'),
              'major_id' => $request->input('major'),
              'nama' => $request->input('name'),
              'tempat_lahir' => $request->input('tempat'),
              'tgl_lahir' => $request->input('tgl'),
              'no_hp' => $request->input('no_hp'),
              'jk' => $request->input('jk')
            ]);
          }
        });
      } catch (\Throwable $th) {
        return response()->json([
          'success' => false,
          'data' => null,
          'msg' => $th
        ], 500);
      }

      switch ($role) {
        case 'admin':
          $data = User::whereRole('admin')->get();
          break;
        case 'student':
          $data = User::whereRole('student')->get();
          break;
        case 'teacher':
          $data = User::whereRole('teacher')->get();
          break;
        default:
          $data = null;
          break;
      }

      return response()->json([
        'success' => true,
        'data' => $data
      ], 201);
    }

    public function show(Request $request, $id)
    {
      // $user = DB::table('users')->where('id', '=', $request->input('id'))->first();
      // $user = DB::table('users')->where('id', '=', $id)->first();
      $user = User::findOrFail($id);
      if ($user) {
        if ($request->ajax()) {
          return response()->json([
            'success' => true,
            'data' => $user
          ], 200);
        } else {
          $majors = Major::all();
          return view('pages.admin.user_teacher_edit', compact('user', 'majors'));
        }
      } else {
        if ($request->ajax()) {
          return response()->json([
            'success' => false,
            'data' => null
          ], 404);
        } else {
          return abort(404);
        }
      }
    }

    public function update(Request $request, $role)
    {
      if ($role == 'admin' || $role == "student") {
        $rules = [
          'password' => 'nullable|min:8|confirmed',
        ];
      } else {
        $rules = [
          'password' => 'nullable|min:8|confirmed',
          'major' => 'required',
          'tempat' => 'required',
          'tgl' => 'required|date_format:Y-m-d',
          'jk' => 'required',
          'no_hp' => 'required|digits_between:11,13'
        ];
      }
      $this->validate($request, $rules);

      // update table user
      $user = User::whereId($request->input('id'))->first();

      if ($request->input('password')) {
        $request->merge(['password' => Hash::make($request->input('password'))]);
        $user->update($request->all());
      } else {
        $user->update($request->except(['password']));
      }

      // update table teacher
      $data = [
        'major_id' => $request->input('major'),
        'nama' => $request->input('name'),
        'tempat_lahir' => $request->input('tempat'),
        'tgl_lahir' => $request->input('tgl'),
        'no_hp' => $request->input('no_hp'),
        'jk' => $request->input('jk')
      ];

      if ($role == 'teacher') {
        $user->teacher()->update($data);
      }

      // Send back response
      if ($user) {
        if ($role == 'student') {
          $res = DB::table('users')
                    ->join('students', 'users.id', '=', 'students.user_id')
                    ->select('users.*', 'students.nama', 'students.nim')
                    ->get();
        } elseif ($role == 'teacher') {
          $res = DB::table('users')
                  ->join('teachers', 'users.id', '=', 'teachers.user_id')
                  ->join('majors', 'majors.id', '=', 'teachers.major_id')
                  ->select('users.*', 'teachers.nama', 'teachers.nidn', 'majors.*')
                  ->get();
        } else {
          $res = User::whereRole($role)->get();
        }

        return response()->json([
          'success' => true,
          'data' => $res
        ], 201);
      } else {
        return response()->json([
          'success' => false,
          'data' => ''
        ], 500);
      }
    }

    public function import(Request $request)
    {
      $this->validate($request, [
        'file' => 'required|mimes:xls,xlsx|max:2048',
      ]);

        // upload
        if($request->file('file')) {
          $fileName = time().'.'.$request->file('file')->extension();
          $path = $request->file('file')->storeAs(
            'public/excel', $fileName
          );
        }

        // import excel using try catch
        $import = new UsersImport;
        try {
          Excel::import($import, 'public/excel/'.$fileName);
        } catch (\Throwable $th) {
          return response()->json([
            'success' => false,
            'data' => null,
            'msg' => $th
          ], 500);
        }

      $teachers = User::whereRole('teacher')->get();
      return response()->json([
        'success' => true,
        'data' => $teachers,
        'msg' => $import->getRowCount().' User berhasil di import'
      ], 200);

    }

    public function showSim($id)
    {
      $user = User::whereId($id)->first();
      $student = DB::table('students')->select('username_sim', 'password_sim')->where('user_id', $user->id)->first();
      if ($user) {
        if ($student->username_sim) {
          return response()->json([
            'success' => true,
            'data' => $student
          ], 200);
        } else {
          return response()->json([
            'success' => true,
            'data' => [
              'username_sim' => '072026'.$user->student->nim
            ]
          ], 200);
        }
      } else {
        return response()->json([
          'success' => false,
          'msg' => 'Data tidak ditemukan'
        ], 404);
      }
    }

    public function updateSim(Request $request)
    {
      $user = User::findOrFail($request->input('id'));
      $user->student->username_sim = $request->input('username_sim');
      $user->student->password_sim = $request->input('password_sim');
      $user->student->save();

      return response()->json([
        'success' => true,
        'msg' => 'Data berhasil diupdate'
      ], 200);
    }




}
