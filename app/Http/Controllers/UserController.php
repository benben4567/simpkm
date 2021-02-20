<?php

namespace App\Http\Controllers;

use App\Major;
use App\Teacher;
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
      $students = User::whereRole('student')->get();
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

      // update table student / teacher
      $data = [
        'major_id' => $request->input('major'),
        'nama' => $request->input('name'),
        'tempat_lahir' => $request->input('tempat'),
        'tgl_lahir' => $request->input('tgl'),
        'no_hp' => $request->input('no_hp'),
        'jk' => $request->input('jk')
      ];

      if ($role == 'student' ) {
        $user->student()->update($data);
      } elseif ($role == 'teacher') {
        $user->teacher()->update($data);
      }

      // Send back response
      if ($user) {
        $res = User::whereRole($role)->get();
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
        try {
          Excel::import(new UsersImport, 'public/excel/'.$fileName);
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
        'msg' => 'Import berhasil'
      ], 200);

    }




}
