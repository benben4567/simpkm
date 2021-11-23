<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Period;
use App\Proposal;
use App\Teacher;
use Illuminate\Support\Facades\DB;

class ProposalController extends Controller
{
  public function index(Request $request)
  {
    if ($request->ajax()) {
      $skema = $request->input('skema');
      $proposals = DB::table('periods')
                  ->join('proposals', 'periods.id', '=', 'proposals.period_id')
                  ->select('periods.id as period_id', 'periods.tahun', 'proposals.id', 'proposals.skema', 'proposals.judul', 'proposals.status')
                  ->where('periods.tahun', '=', $request->input('tahun'))
                  ->when($skema, function($q) use ($skema) {
                    return $q->where('skema', '=', $skema);
                  })
                  ->get();

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

  public function show(Request $request, $id)
  {
    $proposal = Proposal::with('teachers')->whereId($id)->first();
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

  public function update(Request $request)
  {
    $this->validate($request, [
      'pembimbing' => 'required|different:reviewer_1,reviewer_2',
      'reviewer_1' => 'required|different:pembimbing,reviewer_2',
      'reviewer_2' => 'required|different:pembimbing,reviewer_1'
    ]);

    $proposal_id = $request->id;
    $pembimbing = $request->input('pembimbing');
    $reviewer1 = $request->input('reviewer_1');
    $reviewer2 = $request->input('reviewer_2');
    $status = $request->input('status');

    $proposal = Proposal::where('id', $proposal_id);
    $tahun = $proposal->first()->period->tahun;
    $update = $proposal->update(['status' => $status]);
    if ($update) {
      $reviewer = $proposal->first()
                    ->teachers()->sync([
                      $pembimbing => ['jabatan' => 'Pembimbing'],
                      $reviewer1 => ['jabatan' => 'Reviewer 1'],
                      $reviewer2 => ['jabatan' => 'Reviewer 2']
                    ]);

      $proposals = DB::table('periods')
                    ->join('proposals', 'periods.id', '=', 'proposals.period_id')
                    ->select('periods.id as period_id', 'periods.tahun', 'proposals.id', 'proposals.skema', 'proposals.judul', 'proposals.status')
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
                    ->select('periods.id as period_id', 'periods.tahun', 'proposals.id', 'proposals.skema', 'proposals.judul', 'proposals.status')
                    ->where('periods.tahun', '=', $tahun)
                    ->get();

    // $proposals = Periods

    return response()->json([
      'success' => true,
      'data' => $proposals
    ], 200);
  }

  public function destroy(Request $request)
  {
    $proposal = Proposal::where('id', $request->input('id'))->first();

    // delete file
    $proposal = Proposal::whereId($request->input('id'))->first();
    Storage::delete('public/files/'.$proposal->file);

    // delete proposal
    $proposal = Proposal::destroy($request->input('id'));
    if ($proposal) {
      $proposals = DB::table('periods')
                    ->join('proposals', 'periods.id', '=', 'proposals.period_id')
                    ->select('periods.id as period_id', 'periods.tahun', 'proposals.id', 'proposals.skema', 'proposals.judul', 'proposals.status')
                    ->where('periods.tahun', '=', $request->input('tahun'))
                    ->get();

      return response()->json([
        'success' => true,
        'msg' => 'Usulan berhasil dihapus.',
        'data' => $proposals
      ], 200);
    }

  }

}
