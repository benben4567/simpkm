<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\RefSkema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RefSkemaController extends Controller
{
  public function index(Request $request)
  {
    if ($request->ajax()) {
      $skema = RefSkema::all();
      return ResponseFormatter::success($skema, 'Data skema berhasil diambil.');
    }

    return view('pages.admin.skema');
  }

  public function store(Request $request)
  {
    try {
      
      $validator = Validator::make($request->all(), [
        'nama' => 'required',
        'kepanjangan' => 'required'
      ]);
      
      if($validator->fails()) {
        return ResponseFormatter::error($validator->errors(), 'Gagal menambahkan skema. Data tidak valid.', 422);
      }
      
      $skema = new RefSkema();
      $skema->nama = $request->nama;
      $skema->kepanjangan = $request->kepanjangan;
      $skema->save();

      return ResponseFormatter::success($skema, 'Skema berhasil ditambahkan.');
    } catch (\Exception $e) {
      Log::error("RefSkemaController@store: " . $e->getMessage());
      return ResponseFormatter::error($e->getMessage(), 'Gagal menambahkan skema. Server error.');
    }
  }

  public function update(Request $request)
  {
    try {
      $id = $request->id;
      
      $validator = Validator::make($request->all(), [
        'nama' => 'required',
        'kepanjangan' => 'required'
      ]); 
      
      if($validator->fails()) {
        return ResponseFormatter::error($validator->errors(), 'Gagal mengubah skema. Data tidak valid.', 422);
      }

      $skema = RefSkema::find($id);
      $skema->nama = $request->nama;
      $skema->kepanjangan = $request->kepanjangan;
      $skema->save();

      return ResponseFormatter::success($skema, 'Skema berhasil diubah.');
    } catch (\Exception $e) {
      Log::error("RefSkemaController@update: " . $e->getMessage());
      return ResponseFormatter::error($e->getMessage(), 'Gagal mengubah skema. Server error.');
    }
  }

  public function toggle(Request $request)
  {
    try {
      $id = $request->id;

      $skema = RefSkema::find($id);

      $skema->is_aktif = !$skema->is_aktif;

      $skema->save();

      return ResponseFormatter::success($skema, 'Status skema berhasil diubah.');
    } catch (\Exception $e) {
      Log::error("RefSkemaController@toggle: " . $e->getMessage());
      return ResponseFormatter::error($e->getMessage(), 'Gagal mengubah status skema. Server error.');
    }
  }
}
