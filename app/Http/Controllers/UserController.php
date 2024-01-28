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
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request, UserService $userService)
    {
        if ($request->ajax()) {
            switch ($request->input('role')) {
                case 'admin':
                    $admins = $userService->showAll('admin');
                    return ResponseFormatter::success($admins, 'Data ditemukan');
                    break;
                case 'student':
                    $students = $userService->showAll('student');
                    return ResponseFormatter::success($students, 'Data ditemukan');
                    break;
                case 'teacher':
                    $teachers = $userService->showAll('teacher');
                    return ResponseFormatter::success($teachers, 'Data ditemukan');
                    break;
                default:
                    break;
            }
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

    public function store(Request $request, UserService $userService, $role)
    {
        $rules = [
            'email' => 'required|unique:users,email',
            'name' => 'required',
            'password' => 'required|min:8|confirmed'
        ];

        switch ($role) {
            case 'student':
                $rules['nim'] = 'required|unique:students,nim';
                $rules['major'] = 'required';
                $rules['tempat'] = 'required';
                $rules['tgl'] = 'required|date_format:Y-m-d';
                $rules['jk'] = 'required';
                $rules['no_hp'] = 'required|digits_between:11,13';
                $this->validate($request, $rules);
                break;
            case 'teacher':
                $rules['nidn'] = 'required|unique:teachers,nidn';
                $rules['major'] = 'required';
                $rules['tempat'] = 'required';
                $rules['tgl'] = 'required|date_format:Y-m-d';
                $rules['jk'] = 'required';
                $rules['no_hp'] = 'required|digits_between:11,13';
                $this->validate($request, $rules);
                break;
            default:
                $this->validate($request, $rules);
                break;
        }

        $request->merge(['role' => $role]);
        $request->merge(['password' => Hash::make($request->input('password'))]);

        $user = $userService->store($request->all());

        return response()->json([
            'success' => $user['success'],
            'data' => $user['data'],
            'msg' => $user['msg']
        ], $user['code']);

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
