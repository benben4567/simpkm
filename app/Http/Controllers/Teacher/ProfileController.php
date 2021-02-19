<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Major;

class ProfileController extends Controller
{
    public function index()
    {
      $teacher = Auth::user()->teacher;
      $majors = Major::all();
      return view('pages.teacher.profile', compact('teacher', 'majors'));
    }

    public function update(Request $request)
    {
      # code...
    }

    public function updatePassword(Request $request)
    {
      # code...
    }
}
