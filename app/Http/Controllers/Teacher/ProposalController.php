<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Teacher;

class ProposalController extends Controller
{
    public function index()
    {
      $teacher = Teacher::with('proposals')->whereId(Auth::user()->teacher->id)->first();
      // dd($teacher);
      return view('pages.teacher.proposal', compact('teacher'));
    }

    public function review()
    {
      # code...
    }
}
