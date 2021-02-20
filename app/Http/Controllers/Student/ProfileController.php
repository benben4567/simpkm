<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Major;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
      $student = Auth::user()->student;
      $majors = Major::all();
      return view('pages.student.profile', compact('student', 'majors'));
    }

    public function updatePassword(Request $request)
    {
      $this->validate($request,[
        'password' => 'required|min:8|confirmed'
      ]);

      $user = User::whereId($request->input('id'))->update([
        'password' => Hash::make($request->input('password'))
      ]);

      if ($user) {
        if ($request->ajax()) {
          return response()->json([
            'success' => true,
            'msg' => 'Password berhasil diupdate'
          ], 201);
        }
      } else {
        if ($request->ajax()) {
          return response()->json([
            'success' => false,
            'msg' => 'Password gagal diupdate'
          ], 500);
        }
      }
    }
}
