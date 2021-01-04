<?php

namespace App\Http\Controllers;

use App\Exports\ProposalsExport;
use Illuminate\Http\Request;
use App\Period;
use App\Proposal;
use Illuminate\Support\Facades\DB;

class RecapController extends Controller
{
  public function index()
  {
    $periods = Period::all();
    return view('pages.admin.rekap', compact('periods'));
  }

  public function download(Request $request)
  {

    $id = $request->input('periode');
    return (new ProposalsExport($id))->download('invoices.xlsx');
    // $proposals = Proposal::with(['students' =>function($q) {
    //               $q->wherePivot('jabatan', '=', 'Ketua');
    //             }, "teachers" => function($q) {
    //               $q->wherePivot('jabatan', '=', 'Pembimbing');
    //             }])
    //             ->where('period_id', $id)->get();
    // $proposal = DB::table('proposals')
    //             ->join('proposal_student', 'proposals.id', '=', 'proposal_student.proposal_id')
    //             ->join('students', 'proposal_student.student_id', '=', 'students.id')
    //             ->join('proposal_teacher', 'proposals.id', '=', 'proposal_teacher.proposal_id')
    //             ->join('teachers', 'proposal_teacher.teacher_id', '=', 'teachers.id')
    //             ->select('proposals.period_id','proposals.skema','proposals.judul','students.nama AS Ketua','teachers.nama AS Pembimbing')
    //             ->where('proposals.period_id', '=', $id)
    //             ->where('proposal_student.jabatan', '=', 'Ketua')
    //             ->where('proposal_teacher.jabatan', '=', 'Pembimbing')
    //             ->orderBy('proposals.id', 'asc')
    //             ->get();

    // dd($proposals);
    // return view('pages.admin.coba', compact('proposals'));
  }
}
