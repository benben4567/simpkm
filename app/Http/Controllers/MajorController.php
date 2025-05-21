<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Major;
use App\Services\MajorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MajorController extends Controller
{
    public function index(Request $request, MajorService $majorService)
    {
        if ($request->ajax()) {
            $majors = $majorService->showAll();
            return ResponseFormatter::success($majors, 'Data prodi berhasil diambil.');
        }

        return view('pages.admin.prodi');
    }

    public function store(Request $request, MajorService $majorService)
    {
        try {
            $validator = Validator::make($request->all(), [
                'jenjang' => 'required',
                'nama' => 'required',
                'kode_prodi' => 'required',
            ]);
    
            if ($validator->fails()) {
                return ResponseFormatter::error($validator->errors(), 'Data yg dikirim tidak valid', 422);
            }
    
            $major = $majorService->store($request->all());
    
            return ResponseFormatter::success(Major::all(), 'Data berhasil disimpan');
        } catch (\Exception $e) {
            Log::error("MajorController@store: {$e->getMessage()}");
            return ResponseFormatter::error($e->getMessage(), "Gagal menyimpan data. Server Error.", 500);
        }
    }

    public function update(Request $request, MajorService $majorService)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'jenjang' => 'required',
                'nama' => 'required',
            ]);
    
            if ($validator->fails()) {
                return ResponseFormatter::error($validator->errors(), 'Data yang dikirim tidak valid', 422);
            }
    
            $major = $majorService->update($request->all());
    
            return ResponseFormatter::success(Major::all(), 'Data berhasil disimpan');
        } catch (\Exception $e) {
            Log::error("MajorController@update: {$e->getMessage()}");
            return ResponseFormatter::error($e->getMessage(), "Gagal menyimpan data. Server Error.", 500);
        }
    }

    public function toggle(Request $request, MajorService $majorService)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
    
            if ($validator->fails()) {
                return ResponseFormatter::error($validator->errors(), 'Data yang dikirim tidak valid', 422);
            }
    
            $major = $majorService->toggle($request->id);
            return ResponseFormatter::success(Major::all(), 'Berhasil mengubah status prodi.');
        } catch (\Exception $e) {
            Log::error("MajorController@toggle: {$e->getMessage()}");
            return ResponseFormatter::error($e->getMessage(), "Gagal mengubah status prodi. Server Error.", 500);
        }
    }
}
