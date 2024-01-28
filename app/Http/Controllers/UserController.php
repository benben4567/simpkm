<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Imports\StudentImport;
use App\Imports\TeacherImport;
use App\Models\Major;
use App\Services\UserService;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request, UserService $userService)
    {
        if ($request->ajax()) {
            switch ($request->input('role')) {
                case 'admin':
                    $data = $userService->showAll('admin');
                    break;
                case 'student':
                    $data = $userService->showAll('student');
                    break;
                case 'teacher':
                    $data = $userService->showAll('teacher');
                    break;
                default:
                    break;
            }
            
            return ResponseFormatter::success($data, 'Data ditemukan');
        }

        $majors = Major::all();
        return view('pages.admin.user', compact('majors'));
    }

    public function create($role)
    {
        $majors = Major::all();
        switch ($role) {
            case 'student':
                return view('pages.admin.user_student', compact('majors'));
                break;
            case 'teacher':
                return view('pages.admin.user_teacher', compact('majors'));
                break;
            default:
                return abort('404');
                break;
        }
    }

    public function store(Request $request)
    {
        try {
            // Create User Admin
            $validator = Validator::make($request->all(), [
                'email' => 'required|unique:users,email',
                'username' => 'required|unique:users,username',
                'name' => 'required',
                'password' => 'required|min:8|confirmed'
            ]);
            
            if ($validator->fails()) {
                return ResponseFormatter::error($validator->errors(), "Data yang dimasukkan tidak valid. Silahkan coba lagi.", 422);
            }
            
            $user = User::create([
                'email' => $request->email,
                'username' => $request->username,
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ]);
            
            $user->assignRole('admin');
            
            return ResponseFormatter::success($user, 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error("UserController@store: {$e->getMessage()}");
            return ResponseFormatter::error(null, 'Tidak dapat menyimpan data. Kesalahan Server.', 500);
        }
    }

    public function show(Request $request, UserService $userService, $role, $id)
    {
        // $user = DB::table('users')->where('id', '=', $request->input('id'))->first();
        // $user = DB::table('users')->where('id', '=', $id)->first();
        $user = $userService->show($role, $id);

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

    public function update(Request $request, UserService $userService, $role)
    {
        switch ($role) {
            case 'admin':
                $rules = [
                    'password' => 'nullable|min:8|confirmed',
                ];
                break;
            case 'student':
                $rules = [
                    'name' => 'required',
                    'major' => 'required',
                    'nim' => 'required',
                    'email' => 'required',
                    'password' => 'nullable|min:8|confirmed',
                ];
                break;
            case 'teacher':
                $rules = [
                    'name' => 'required',
                    'major' => 'required',
                    'nidn' => 'required',
                    'email' => 'required',
                    'password' => 'nullable|min:8|confirmed',
                ];
                break;
            default:
                break;
        }

        $this->validate($request, $rules);

        // update table user
        $update = $userService->update($request->all(), $role);

        return response()->json([
            'success' => $update['success'],
            'data' => $update['data'],
            'msg' => $update['msg'],
        ], $update['code']);
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx|max:2048',
        ]);

        // upload
        if ($request->file('file')) {
            $fileName = time() . '.' . $request->file('file')->extension();
            $path = $request->file('file')->storeAs(
                'public/excel',
                $request->input('role') . '_import' . $fileName
            );
        }

        if ($request->input('role') == 'student') {
            $import = new StudentImport;
        } else {
            $import = new TeacherImport;
        }
        // import excel using try catch
        try {
            Excel::import($import, $path);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'msg' => $e->getMessage(),
            ], 500);
        }

        if ($request->input('role') == 'student') {
            $users = User::whereRole('student')->with('student')->get();
        } else {
            $users = User::whereRole('teacher')->with('teacher')->get();
        }

        return response()->json([
            'success' => true,
            'data' => $users,
            'msg' => $import->getRowCount() . ' User berhasil di import.'
        ], 200);

    }

    public function showSim(UserService $userService, $id)
    {
        $sim = $userService->showSim($id);

        return response()->json([
            'success' => $sim['success'],
            'data' => $sim['data'],
            'msg' => $sim['msg'],
        ], $sim['code']);
    }

    public function updateSim(Request $request, UserService $userService)
    {
        $update = $userService->updateSim($request->all());

        return response()->json([
            'success' => $update['success'],
            'data' => $update['data'],
            'msg' => $update['msg']
        ], $update['code']);
    }
}
