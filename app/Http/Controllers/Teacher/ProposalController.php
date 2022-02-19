<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Teacher;
use App\Proposal;
use App\Period;

class ProposalController extends Controller
{
    public function index(Request $request)
    {
      $periods = Period::all()->sortByDesc('tahun');
      $now = $periods->first();

      if ($now) {
        if ($request->input('periode')) {
          $periode = $request->input('periode');
          $now = Period::where('id', $periode)->first();

          $teacher = Teacher::with(['proposals' => function($q) use ($now) {
                  $q->where('period_id', $now->id);
                  $q->wherePivot('jabatan', '=', 'Pembimbing')->get();
                }])->whereId(Auth::user()->teacher->id)->first();

          return view('pages.teacher.proposal', compact('teacher', 'periods', 'now', 'periode'));
        } else {
          // Only show if jabatan == Pembimbing
          $teacher = Teacher::with(['proposals' => function($q) use ($now) {
                      $q->where('period_id', $now->id);
                      $q->wherePivot('jabatan', '=', 'Pembimbing')->get();
                    }])->whereId(Auth::user()->teacher->id)->first();
        }
      } else {
        $teacher = Teacher::with(['proposals' => function($q) use ($now) {
          $q->wherePivot('jabatan', '=', 'Pembimbing')->get();
        }])->whereId(Auth::user()->teacher->id)->first();
      }

      return view('pages.teacher.proposal', compact('teacher', 'periods', 'now'));
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

    public function review(Request $request)
    {
      $periods = Period::all()->sortByDesc('tahun');
      $now = $periods->first();

      if ($now) {
        if ($request->input('periode')) {
          $periode = $request->input('periode');
          $now = Period::where('id', $periode)->first();

          $teacher = Teacher::with(['proposals' => function($q) use ($now){
                        $q->where('period_id', $now->id);
                        $q->wherePivot('jabatan', '!=', 'Pembimbing')->get();
                    }])->whereId(Auth::user()->teacher->id)->first();

          return view('pages.teacher.reviewer', compact('teacher', 'periods', 'now', 'periode'));
        } else {
          // Only show if jabatan == Reviewer
          $teacher = Teacher::with(['proposals' => function($q) use ($now) {
            $q->where('period_id', $now->id);
            $q->wherePivot('jabatan', '!=', 'Pembimbing')->get();
          }])->whereId(Auth::user()->teacher->id)->first();
        }
      } else {
        // Only show if jabatan == Reviewer
        $teacher = Teacher::with(['proposals' => function($q) use ($now) {
          $q->wherePivot('jabatan', '!=', 'Pembimbing')->get();
        }])->whereId(Auth::user()->teacher->id)->first();
      }

      return view('pages.teacher.reviewer', compact('teacher', 'periods', 'now'));
    }

    public function download(Request $request)
    {
      $id = $request->id;

      $proposal = Proposal::whereId($id)->first();
      $skema = $proposal->skema;

      $data = array(
        '[KETUA]' => $proposal->ketua->first()->nama,
        '[NIM]' => $proposal->ketua->first()->nim,
        '[PRODI]' => $proposal->ketua->first()->major->full_name,
        '[EMAIL]' => $proposal->ketua->first()->user->email,
        '[PENDAMPING]' => $proposal->pembimbing->first()->nama,
        '[JUDUL]' => $proposal->judul,
        '[REVIEWER1]' => $proposal->reviewer1->first()->nama,
        '[REVIEWER2]' => $proposal->reviewer2->first()->nama
      );

      $this->exportForm($data,$skema);
    }

    public function download2(Request $request)
    {
      $id = $request->id;
      $proposal = Proposal::whereId($id)->first();
      $periode = $proposal->period->tahun;
      $skema = $proposal->skema;

      $data = array(
        '[PEMBUKAAN]' => $proposal->period->tahun,
        '[PENDANAAN]' => $periode + 1,
        '[KETUA]' => $proposal->ketua->first()->nama,
        '[PENDAMPING]' => $proposal->pembimbing->first()->nama,
        '[JUDUL]' => $proposal->judul,
        '[SKEMA]' => $proposal->skema,
        '[REVIEWER1]' => $proposal->reviewer1->first()->nama,
        '[REVIEWER2]' => $proposal->reviewer2->first()->nama,
        '[NIDNREVIEWER1]' => $proposal->reviewer1->first()->nidn,
      );

      $this->exportBerita($data, $skema);
    }

    public function exportBerita($data, $skema)
    {
      if ($skema == 'PKM-GFK') {
        $file = asset('template/BA-GFK.rtf');
      } elseif ($skema == 'PKM-AI' || $skema == 'PKM-GT') {
        $file = asset('template/BA-GT-AI.rtf');
      } else {
        $file = asset('template/BA-5Bidang.rtf');
      }

      $rand = uniqid();
      $nama_file = 'BA_'.$rand.'.doc';

      return \WordTemplate::export($file, $data, $nama_file);
    }

    public function exportForm($data,$skema)
    {
      $file = asset('template/'.$skema.'.rtf');

      $rand = uniqid();
      $nama_file = 'FORM_'.$skema.'_'.$rand.'.doc';

      return \WordTemplate::export($file, $data, $nama_file);
    }

    public function print(Request $request)
    {
      if ($request->input('jenis') == 'usulan') {
        $teacher = Teacher::with(['proposals' => function($q) {
          $q->wherePivot('jabatan', '=', 'Pembimbing')->get();
        }])->whereId(Auth::user()->teacher->id)->first();

        $period = $teacher->proposals->first();
        $tahun = $period->period->tahun;

        return view('pages.teacher.print_usulan', compact('teacher','tahun'));

      } else {
        $teacher = Teacher::with(['proposals' => function($q) {
          $q->wherePivot('jabatan', '!=', 'Pembimbing')->get();
        }])->whereId(Auth::user()->teacher->id)->first();

        $period = $teacher->proposals->first();
        $tahun = $period->period->tahun;
        return view('pages.teacher.print_reviewer', compact('teacher','tahun'));
      }



    }
}
