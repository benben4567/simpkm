<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Proposal;
use App\Models\Major;
use App\Models\Student;
use App\Models\Period;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->roles->pluck('name')[0] == 'admin') {
            $proposal = Proposal::all()->count();
            $lolos = Proposal::lolos()->count();
            $major = Major::all()->count();
            $student = Student::all()->count();
            $periods = Period::all();
            return view('pages.admin.index', compact('proposal', 'lolos', 'major', 'student', 'periods'));
        } elseif (Auth::user()->roles->pluck('name')[0] == 'student') {
            $proposals = Proposal::with('teachers')->whereHas('students', function ($q) {
                $q->where('student_id', '=', Auth::user()->student->id);
            })->get();
            // dd($proposals);
            return view('pages.student.index', compact('proposals'));
        } elseif (Auth::user()->roles->pluck('name')[0] == 'teacher') {
            $proposals = Proposal::with('students')->whereHas('teachers', function ($q) {
                $q->where('teacher_id', '=', Auth::user()->teacher->id);
            })->get();
            return view('pages.teacher.index', compact('proposals'));
        } else {
            return abort(403);
        }
    }

    public function recap($tahun)
    {
        $period = Period::whereTahun($tahun)->first();
        if ($period) {
            $recap = $period->proposals->groupBy('skema')->map->count();
            return response()->json([
                'success' => true,
                'data' => $recap
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => null
            ], 404);
        }

    }

    public function chart()
    {
        $periods = Period::withCount('proposals')->get()->pluck('proposals_count', 'tahun')->toArray();
        return response()->json([
            'success' => true,
            'data' => $periods
        ], 200);
    }

    public function panduan()
    {
        return view('pages.student.panduan');
    }
    
    public function changePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'password' => 'required|confirmed|min:8',
            ]);
            
            if ($validator->fails()) {
                return ResponseFormatter::error($validator->errors(), 'Data tidak valid.', 422);
            }
            
            if (!Hash::check($request->old_password, Auth::user()->password)) {
                return ResponseFormatter::error([
                    'old_password' => ['Password lama tidak sesuai.']
                ], 'Password lama tidak sesuai.', 422);
            }
            
            $user = Auth::user();
            $user->password = Hash::make($request->password);
            $user->save();
            return ResponseFormatter::success($user, 'Password berhasil diubah.');
        } catch (\Exception $e) {
            Log::error("Change Password: {$e->getMessage()}");
            return ResponseFormatter::error($e->getMessage(), 'Password gagal diubah. Terjadi kesalahan Server.', 500);
        }
    }
}
