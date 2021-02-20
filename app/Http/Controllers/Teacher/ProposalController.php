<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Teacher;
use App\Proposal;

class ProposalController extends Controller
{
    public function index()
    {
      $teacher = Teacher::with('proposals')->whereId(Auth::user()->teacher->id)->first();
      // dd($teacher);
      return view('pages.teacher.proposal', compact('teacher'));
    }

    public function show(Request $request, $id)
    {
      $proposal = Proposal::whereId($id)->first();
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

    public function review()
    {
      # code...
    }
}
