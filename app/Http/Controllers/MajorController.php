<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MajorController extends Controller
{
    public function index(Request $request)
    {
      if ($request->ajax()) {
        $majors = Major::all();
        return ResponseFormatter::success($majors, 'Data ditemukan');
      }

      return view('pages.admin.prodi');
    }

    public function store(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'jenjang' => 'required',
        'nama' => 'required',
      ]);

      if ($validator->fails()) {
        return ResponseFormatter::error($validator->errors(), 'Data yg dikirim tidak valid', 422);
      }

      $major = Major::create([
        'degree' => $request->input('jenjang'),
        'name' => $request->input('nama'),
      ]);

      if ($major) {
        return ResponseFormatter::success(Major::all(), 'Data berhasil disimpan');
      } else {
        return ResponseFormatter::error(null, 'Data gagal disimpan', 500);
      }
    }

    public function show($id)
    {
      $major = Major::find($id);
      if ($major) {
        return ResponseFormatter::success($major, 'Data ditemukan');
      } else {
        return ResponseFormatter::error(null, 'Data tidak ditemukan', 404);
      }
    }

    public function update(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'id' => 'required',
        'jenjang' => 'required',
        'nama' => 'required',
      ]);

      if ($validator->fails()) {
        return ResponseFormatter::error($validator->errors(), 'Data yang dikirim tidak valid', 422);
      }

      $major = Major::where('id', $request->id)->update([
        'degree' => $request->jenjang,
        'name' => $request->nama,
      ]);

      if ($major) {
        return ResponseFormatter::success(Major::all(), 'Data berhasil disimpan');
      } else {
        return ResponseFormatter::error(null, 'Data gagal disimpan', 500);
      }
    }
}
