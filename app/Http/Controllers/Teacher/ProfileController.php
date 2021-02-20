<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Major;
use App\User;

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
