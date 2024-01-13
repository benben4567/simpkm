<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Major;
use App\Services\MajorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MajorController extends Controller
{
  public function index(Request $request, MajorService $majorService)
  {
    if ($request->ajax()) {
      $majors = $majorService->showAll();
      return ResponseFormatter::success($majors, 'Data ditemukan');
    }

    return view('pages.admin.prodi');
  }

  public function store(Request $request, MajorService $majorService)
  {
    $validator = Validator::make($request->all(), [
      'jenjang' => 'required',
      'nama' => 'required',
    ]);

    if ($validator->fails()) {
      return ResponseFormatter::error($validator->errors(), 'Data yg dikirim tidak valid', 422);
    }

    $major = $majorService->store($request->all());

    if ($major) {
      return ResponseFormatter::success(Major::all(), 'Data berhasil disimpan');
    } else {
      return ResponseFormatter::error(null, 'Data gagal disimpan', 500);
    }
  }

  public function show(MajorService $majorService, $id)
  {
    $major = $majorService->show($id);
    if ($major) {
      return ResponseFormatter::success($major, 'Data ditemukan');
    } else {
      return ResponseFormatter::error(null, 'Data tidak ditemukan', 404);
    }
  }

  public function update(Request $request, MajorService $majorService)
  {
    $validator = Validator::make($request->all(), [
      'id' => 'required',
      'jenjang' => 'required',
      'nama' => 'required',
    ]);

    if ($validator->fails()) {
      return ResponseFormatter::error($validator->errors(), 'Data yang dikirim tidak valid', 422);
    }

    $major = $majorService->update($request->all());

    if ($major) {
      return ResponseFormatter::success(Major::all(), 'Data berhasil disimpan');
    } else {
      return ResponseFormatter::error(null, 'Data gagal disimpan', 500);
    }
  }
}
