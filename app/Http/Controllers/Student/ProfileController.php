<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\User;
use App\Models\Student;
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

  public function update(Request $request)
  {
    $this->validate($request, [
      'nama' => 'required',
      'major' => 'required',
      'tempat' => 'required',
      'tgl' => 'required',
      'jk' => 'required',
      'no_hp' => 'required|digits_between:9,13'
    ]);

    if ($request->input('id')) {
      // Update record
      $student = Student::whereId($request->input('id'))->update([
        'nama' => $request->input('nama'),
        'major_id' => $request->input('major'),
        'tempat_lahir' => $request->input('tempat'),
        'tgl_lahir' => $request->input('tgl'),
        'jk' => $request->input('jk'),
        'no_hp' => $request->input('no_hp')
      ]);
      // Create response
      if ($student) {
        return response()->json([
          'success' => true,
          'msg' => 'Data berhasil diupdate.',
          'data' => Auth::user()->teacher
        ], 200);
      } else {
        return response()->json([
          'success' => false,
          'msg' => 'Data gagal diupdate',
        ], 500);
      }
    }
  }
}
