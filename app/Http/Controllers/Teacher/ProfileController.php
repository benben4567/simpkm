<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Major;
use App\Models\User;
use App\Models\Teacher;

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
    $this->validate($request, [
      'name' => 'required',
      'major' => 'required',
      'tempat' => 'required',
      'tgl' => 'required',
      'jk' => 'required',
      'no_hp' => 'required|digits_between:9,13'
    ]);

    if ($request->input('id')) {
      // update record
      $teacher = Teacher::whereId($request->input('id'))->update([
        'major_id' => $request->input('major'),
        'nama' => $request->input('name'),
        'jk' => $request->input('jk'),
        'tempat_lahir' => $request->input('tempat'),
        'tgl_lahir' => $request->input('tgl'),
        'no_hp' => $request->input('no_hp'),
      ]);
      // create response
      if ($teacher) {
        return response()->json([
          'success' => true,
          'msg' => 'Data berhasil diupdate.',
          'data' => Auth::user()->teacher
        ], 200);
      } else {
        return response()->json([
          'success' => false,
          'msg' => 'Data gagal diupdate.',
          'data' => ''
        ], 500);
      }
    }
  }
}
