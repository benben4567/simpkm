<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
      $student = Auth::user()->student;
      $majors = Major::all();
      return view('pages.student.profile', compact('student', 'majors'));
    }
}
