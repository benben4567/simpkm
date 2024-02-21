<?php

namespace App\Http\Controllers\Teacher;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Jobs\UploadReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use App\Models\Proposal;
use App\Models\Period;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

                $teacher = Teacher::with([
                    'proposals' => function ($q) use ($now) {
                        $q->where('period_id', $now->id);
                        $q->wherePivot('jabatan', '=', 'Pembimbing')->get();
                    }
                ])->whereId(Auth::user()->teacher->id)->first();

                return view('pages.teacher.proposal', compact('teacher', 'periods', 'now', 'periode'));
            } else {
                // Only show if jabatan == Pembimbing
                $teacher = Teacher::with([
                    'proposals' => function ($q) use ($now) {
                        $q->where('period_id', $now->id);
                        $q->wherePivot('jabatan', '=', 'Pembimbing')->get();
                    }
                ])->whereId(Auth::user()->teacher->id)->first();
            }
        } else {
            $teacher = Teacher::with([
                'proposals' => function ($q) use ($now) {
                    $q->wherePivot('jabatan', '=', 'Pembimbing')->get();
                }
            ])->whereId(Auth::user()->teacher->id)->first();
        }

        return view('pages.teacher.proposal', compact('teacher', 'periods', 'now'));
    }

    public function show(Request $request, $id)
    {
        $proposal = Proposal::whereId($id)->first();
        $ketua = $proposal->ketua->first();
        $pembimbing = $proposal->pembimbing->first();
        $reviewer = $proposal->reviewer->first();
        $anggota = $proposal->anggota->toArray();
        if ($request->ajax()) {
            if ($proposal) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'proposal' => $proposal,
                        'ketua' => $ketua,
                        'pembimbing' => $pembimbing,
                        'reviewer' => $reviewer,
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

                $teacher = Teacher::with([
                    'proposals' => function ($q) use ($now) {
                        $q->where('period_id', $now->id);
                        $q->wherePivot('jabatan', 'reviewer')->get();
                    }
                ])->whereId(Auth::user()->teacher->id)->first();

                return view('pages.teacher.reviewer', compact('teacher', 'periods', 'now', 'periode'));
            } else {
                // Only show if jabatan == Reviewer
                $teacher = Teacher::with([
                    'proposals' => function ($q) use ($now) {
                        $q->where('period_id', $now->id);
                        $q->wherePivot('jabatan', 'reviewer')->get();
                    }
                ])->whereId(Auth::user()->teacher->id)->first();
            }
        } else {
            // Only show if jabatan == Reviewer
            $teacher = Teacher::with([
                'proposals' => function ($q) use ($now) {
                    $q->wherePivot('jabatan', '!=', 'Pembimbing')->get();
                }
            ])->whereId(Auth::user()->teacher->id)->first();
        }

        return view('pages.teacher.reviewer', compact('teacher', 'periods', 'now'));
    }

    public function reviewer($id)
    {
        $proposal = Proposal::whereId($id)->with('reviews.user')->first();
        $periode = $proposal->period->first();
        $ketua = $proposal->ketua->first();
        $pembimbing = $proposal->pembimbing->first();
        $reviewer = $proposal->reviewer->first();
        $anggota = $proposal->anggota->toArray();
        
        return view('pages.teacher.reviewer_detail', compact('proposal', 'periode', 'ketua', 'pembimbing', 'reviewer', 'anggota'));
    }

    public function reviewerStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id-proposal' => 'required',
                'id-folder' => 'required',
                'deskripsi' => 'required',
                'file' => 'required|mimes:pdf|max:5120',
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error(null, $validator->errors(), 422);
            }
            
            // upload file to temporary folder
            $filename_temp = time() . '.' . $request->file('file')->getClientOriginalExtension();
            Storage::putFileAs('public/temp_proposal_review', $request->file('file'), $filename_temp);
            
            $user = [
                'id' => auth()->user()->id,
                'roles' => auth()->user()->roles->pluck('name')[0],
            ];
            
            UploadReview::dispatch($user, $filename_temp, $request->input('id-folder'), $request->input('id-proposal'), $request->deskripsi, null);

            return ResponseFormatter::success(null, 'Data Berhasil disimpan', 201);
        } catch (\Exception $e) {
            Log::error("ProposalController::reviewerStore() " . $e->getMessage());
            return ResponseFormatter::error(null, 'Data Gagal disimpan', 500);
        }
    }

    public function reviewerAcc(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id-proposal' => 'required',
                'id-folder' => 'required',
                'deskripsi' => 'nullable',
                'file' => 'required|file|max:5120',
            ]);
    
            if ($validator->fails()) {
                return ResponseFormatter::error(null, $validator->errors(), 422);
            }
    
            $filename_temp = time() . '.' . $request->file('file')->getClientOriginalExtension();
            Storage::putFileAs('public/temp_proposal_review', $request->file('file'), $filename_temp);
            
            $user = [
                'id' => auth()->user()->id,
                'roles' => auth()->user()->roles->pluck('name')[0],
            ];
            
            UploadReview::dispatch($user, $filename_temp, $request->input('id-folder'), $request->input('id-proposal'), $request->deskripsi, 1);
    
            return ResponseFormatter::success(null, 'Data Berhasil disimpan', 201);
        } catch (\Exception $e) {
            Log::error("ProposalController::reviewerAcc() " . $e->getMessage());
            return ResponseFormatter::error(null, 'Data Gagal disimpan', 500);
        }
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
            '[REVIEWER]' => $proposal->reviewer->first()->nama,
        );

        $this->exportForm($data, $skema);
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
            '[REVIEWER]' => $proposal->reviewer->first()->nama,
            '[NIDNREVIEWER]' => $proposal->reviewer->first()->nidn,
        );

        $this->exportBerita($data, $skema);
    }

    public function downloadProposal(Request $request)
    {
        // $proposal = Proposal::whereId($request->id)->first();
        $metadata = Storage::cloud()->getMetadata($request->file);
        $download = Storage::cloud()->download($request->file, $metadata['name']);
        return $download;
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
        $nama_file = 'BA_' . $rand . '.doc';

        return \WordTemplate::export($file, $data, $nama_file);
    }

    public function exportForm($data, $skema)
    {
        $file = asset('template/' . $skema . '.rtf');

        $rand = uniqid();
        $nama_file = 'FORM_' . $skema . '_' . $rand . '.doc';

        return \WordTemplate::export($file, $data, $nama_file);
    }

    public function print(Request $request)
    {
        if ($request->input('jenis') == 'usulan') {
            $teacher = Teacher::with([
                'proposals' => function ($q) {
                    $q->wherePivot('jabatan', '=', 'Pembimbing')->get();
                }
            ])->whereId(Auth::user()->teacher->id)->first();

            $period = $teacher->proposals->first();
            $tahun = $period->period->tahun;

            return view('pages.teacher.print_usulan', compact('teacher', 'tahun'));

        } else {
            $teacher = Teacher::with([
                'proposals' => function ($q) {
                    $q->wherePivot('jabatan', '!=', 'Pembimbing')->get();
                }
            ])->whereId(Auth::user()->teacher->id)->first();

            $period = $teacher->proposals->first();
            $tahun = $period->period->tahun;
            return view('pages.teacher.print_reviewer', compact('teacher', 'tahun'));
        }



    }
}
