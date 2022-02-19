<?php

namespace App\Http\Controllers;

use App\Period;
use App\Services\PeriodService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PeriodController extends Controller
{
    public function index(Request $request, PeriodService $periodService)
    {
      $periods = $periodService->showAll();

      if ($request->ajax()) {
        return response()->json([
          'success' => true,
          'data' => $periods,
          'msg' => ''
        ], 200);
      }

      return view('pages.admin.period', compact('periods'));
    }

    public function store(Request $request, PeriodService $periodService)
    {
      $this->validate($request,[
        'tahun' => 'required|unique:periods,tahun'
      ]);

      $period = $periodService->store($request->all());

      // buat folder di google drive
      $year = $request->tahun;
      $dir = Storage::cloud()->makeDirectory($year);
      if ($dir) {
        $contents = collect(Storage::cloud()->listContents('/', false));
        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', $year)
            ->first();
        // get directory id
        $id_directory = $dir['path'];
      }

      // update id_folder
      $update = Period::where('id', $period->id)->update([
        'id_folder' => $id_directory
      ]);

      // jika berhasil disimpan
      if ($update) {
        $periods = Period::withCount('proposals')->get();

        return response()->json([
          'success' => true,
          'data' => $periods,
          'msg' => 'Data berhasil disimpan.'
        ], 201);
      } else {
        return response()->json([
          'success' => false,
          'data' => null,
          'msg' => 'Data gagal disimpan.'
        ]);
      }
    }

    public function show(Request $request, PeriodService $periodService)
    {
      $period = $periodService->show($request->all());
      if ($period) {
        return response()->json([
          'success' => true,
          'data' => $period,
        ], 200);
      } else {
        return response()->json([
          'success' => false,
          'data' => null,
        ], 404);
      }
    }

    public function update(Request $request, PeriodService $periodService)
    {
      $period = $periodService->update($request->all());

      if ($period) {
        $periods = Period::withCount('proposals')->get();
        return response()->json([
          'success' => true,
          'data' => $periods,
          'msg' => 'Data berhasil disimpan.'
        ], 201);
      } else {
        return response()->json([
          'success' => false,
          'data' => null,
          'msg' => 'Data gagal disimpan.'
        ]);
      }
    }
}
