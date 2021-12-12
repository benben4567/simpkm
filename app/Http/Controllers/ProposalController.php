<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Period;
use App\Proposal;
use App\Services\AdminProposalService;
use App\Teacher;
use Illuminate\Support\Facades\DB;

class ProposalController extends Controller
{
  public function index(Request $request, AdminProposalService $adminProposalService)
  {
    if ($request->ajax()) {
      $proposals = $adminProposalService->showAll($request->all());

      return response()->json([
        'success' => true,
        'data' => $proposals
      ], 200);
    }

    $periods = Period::orderBy('tahun', 'DESC')->get();
    $teachers = Teacher::orderBy('nama', 'ASC')->get();
    $now = $periods->first()->id;
    $proposals = Proposal::where('period_id', $now)->get();
    return view('pages.admin.usulan', compact('periods', 'teachers', 'proposals'));
  }

  public function show(Request $request, AdminProposalService $adminProposalService, $id)
  {
    $proposal = $adminProposalService->show($id);

    $ketua = $proposal->ketua->first();
    $pembimbing = $proposal->pembimbing->first();
    $reviewer1 = $proposal->reviewer1->first();
    $reviewer2 = $proposal->reviewer2->first();
    $anggota = $proposal->anggota->toArray();
    if ($request->ajax()) {
      if ($proposal) {
        return response()->json([
          'success' => true,
          'data' => [
            'proposal' => $proposal,
            'ketua' => $ketua,
            'pembimbing' => $pembimbing,
            'reviewer1' => $reviewer1,
            'reviewer2' => $reviewer2,
            'anggota' => $anggota
        ],
        ]);
      } else {
        return response()->json([
          'success' => false,
          'msg' => 'Data not found.'
        ], 404);
      }
    }
  }

  public function update(Request $request, AdminProposalService $adminProposalService)
  {
    $this->validate($request, [
      'pembimbing' => 'required|different:reviewer_1,reviewer_2',
      'reviewer_1' => 'required|different:pembimbing,reviewer_2',
      'reviewer_2' => 'required|different:pembimbing,reviewer_1'
    ]);

    $update = $adminProposalService->update($request->all());
    if ($update) {
      return response()->json([
        'success' => true,
        'data' => $update
      ], 201);
    } else {
      return response()->json([
        'success' => false,
        'data' => null
      ], 500);
    }
  }

  public function nilai(Request $request)
  {
    $this->validate($request, [
      'id-proposal' => 'required',
      'nilai1' => 'required|numeric',
      'nilai2' => 'required|numeric',
    ]);

    $proposal = Proposal::where('id', $request->input('id-proposal'));
    $tahun = $proposal->first()->period->tahun;
    $update = $proposal->update(['nilai1' => $request->input('nilai1'), 'nilai2' => $request->input('nilai2')]);

    if($update) {
      $proposals = DB::table('periods')
                      ->join('proposals', 'periods.id', '=', 'proposals.period_id')
                      ->select('periods.id as period_id', 'periods.tahun', 'proposals.id', 'proposals.skema', 'proposals.judul', 'proposals.status', 'proposals.nilai1', 'proposals.nilai2')
                      ->where('periods.tahun', '=', $tahun)
                      ->get();

      return response()->json([
        'success' => true,
        'data' => $proposals
      ], 201);
    } else {
      return response()->json([
        'success' => false,
        'data' => null
      ], 500);
    }
  }

  public function print($tahun)
  {
    $proposals = DB::table('periods')
                    ->join('proposals', 'periods.id', '=', 'proposals.period_id')
                    ->select('periods.id as period_id', 'periods.tahun', 'proposals.id', 'proposals.skema', 'proposals.judul', 'proposals.status',  'proposals.nilai1', 'proposals.nilai2')
                    ->where('periods.tahun', '=', $tahun)
                    ->get();

    // $proposals = Periods

    return response()->json([
      'success' => true,
      'data' => $proposals
    ], 200);
  }

  public function destroy(Request $request, AdminProposalService $adminProposalService)
  {
    $proposals = $adminProposalService->delete($request->all());
    if ($proposals) {
      return response()->json([
        'success' => true,
        'msg' => 'Usulan berhasil dihapus.',
        'data' => $proposals
      ], 200);
    }

  }

}
