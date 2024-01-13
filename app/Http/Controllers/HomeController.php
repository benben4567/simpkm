<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Proposal;
use App\Models\Major;
use App\Models\Student;
use App\Models\Period;

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

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
    if (Auth::user()->role == 'admin') {
      $proposal = Proposal::all()->count();
      $lolos = Proposal::lolos()->count();
      $major = Major::all()->count();
      $student = Student::all()->count();
      $periods = Period::all();
      return view('pages.admin.index', compact('proposal', 'lolos', 'major', 'student', 'periods'));
    } elseif (Auth::user()->role == 'student') {
      $proposals = Proposal::with('teachers')->whereHas('students', function ($q) {
        $q->where('student_id', '=', Auth::user()->student->id);
      })->get();
      // dd($proposals);
      return view('pages.student.index', compact('proposals'));
    } elseif (Auth::user()->role == 'teacher') {
      $proposals = Proposal::with('students')->whereHas('teachers', function ($q) {
        $q->where('teacher_id', '=', Auth::user()->teacher->id);
      })->get();
      return view('pages.teacher.index', compact('proposals'));
    } else {
      return abort(403);
    }
    return view('home');
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
}
