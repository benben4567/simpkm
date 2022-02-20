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

      // jika berhasil disimpan
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
