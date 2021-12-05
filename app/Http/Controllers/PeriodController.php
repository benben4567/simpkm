<?php

namespace App\Http\Controllers;

use App\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function index(Request $request)
    {
      $periods = Period::withCount('proposals')->get();

      if ($request->ajax()) {
        return response()->json([
          'success' => true,
          'data' => $periods,
          'msg' => ''
        ], 200);
      }

      return view('pages.admin.period', compact('periods'));
    }

    public function store(Request $request)
    {
      $this->validate($request,[
        'tahun' => 'required|unique:periods,tahun'
      ]);

      // cek apakah ada periode yang belum ditutup
      $count = Period::where('status', '=', 'buka')->count();
      if ($count == 0) {
        $period = Period::create([
          'tahun' => $request->input('tahun'),
          'status' => 'buka'
        ]);
      } else {
        return response()->json([
          'success' => false,
          'data' => null,
          'msg' => 'Ada periode PKM yang belum ditutup.'
        ]);
      }

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

    public function show(Request $request)
    {
      $period = Period::whereId($request->input('id'))->first();
      if ($period) {
        return response()->json([
          'success' => true,
          'data' => $period,
        ], 200);
      } else {
        return response()->json([
          'success' => true,
          'data' => null,
        ], 200);
      }
    }

    public function update(Request $request)
    {
      if ($request->input('status') == 'buka') {
        $count_period = Period::count();
        $count_open = Period::where('status', '=', 'buka')->count();
        if ($count_period <=1) {
          $period = Period::whereId($request->input('id'))->update([
            'status' => $request->input('status'),
            'pendaftaran' => $request->input('pendaftaran')
          ]);
        } else {
          if ($count_open == 0) {
            $period = Period::whereId($request->input('id'))->update([
              'status' => $request->input('status'),
              'pendaftaran' => $request->input('pendaftaran')
            ]);
          } else {
            return response()->json([
              'success' => false,
              'msg' => 'Tidak bisa membuka periode. Ada periode yang belum ditutup.'
            ]);
          }
        }
      } else {
        $period = Period::whereId($request->input('id'))->update([
          'status' => $request->input('status'),
          'pendaftaran' => $request->input('pendaftaran')
        ]);
      }

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
