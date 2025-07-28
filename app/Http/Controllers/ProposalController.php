<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Period;
use App\Models\Proposal;
use App\Services\AdminProposalService;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

    if (count($periods)) {
      $now = $periods->first()->id;
      $proposals = Proposal::where('period_id', $now)->get();
    } else {
      $proposals = Proposal::all();
    }

    return view('pages.admin.usulan', compact('periods', 'teachers', 'proposals'));
  }

  public function show(Request $request, AdminProposalService $adminProposalService, $id)
  {
    $proposal = $adminProposalService->show($id);

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

  public function update(Request $request, AdminProposalService $adminProposalService)
  {
    $this->validate($request, [
      'pembimbing' => 'required|different:reviewer',
      'reviewer' => 'required|different:pembimbing',
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

  public function review($id)
  {

    $proposal = Proposal::whereId($id)->with('reviews.user')->first();
    $periode = $proposal->period->first();
    $ketua = $proposal->ketua->first();
    $pembimbing = $proposal->pembimbing->first();
    $reviewer = $proposal->reviewer->first();
    $anggota = $proposal->anggota->toArray();

    return view('pages.admin.review', compact('proposal', 'periode', 'ketua', 'pembimbing', 'reviewer', 'anggota'));
  }

  public function reviewStore(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id-proposal' => 'required',
      'deskripsi' => 'required',
      'file' => 'required|file|max:5120',
    ]);

    if ($validator->fails()) {
      return ResponseFormatter::error(null, $validator->errors(), 422);
    }

    $proposal = Proposal::find($request->input('id-proposal'));

    // if exist upload and save to table
    $title = preg_replace('/[^a-z0-9]+/', '-', strtolower(Str::words($proposal->judul, 7, '')));
    $filename = $proposal->skema . '_' . $title . '_' . time() . '.pdf';

    $file = Storage::cloud()->putFileAs($request->input('id-folder'), $request->file('file'), $filename);
    $metadata = Storage::cloud()->getMetadata($file);
    $id_file = $metadata['path'];

    // simpan ke table proposal
    $review = $proposal->reviews()->create([
      'user_id' => auth()->user()->id,
      'type' => auth()->user()->roles->pluck('name')[0],
      'description' => $request->deskripsi,
      'file' => $id_file
    ]);

    if ($review) {
      return ResponseFormatter::success($review, 'Data Berhasil disimpan', 201);
    } else {
      return ResponseFormatter::error(null, 'Data Gagal disimpan', 500);
    }
  }

  public function reviewAcc(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id-proposal' => 'required',
      'id-folder' => 'required',
      'deskripsi' => 'nullable',
      'file' => 'required|file|max:5120',
    ]);

    if ($validator->fails()) {
      return ResponseFormatter::error(null, $validator->errors(), 422);
    }

    $proposal = Proposal::find($request->input('id-proposal'));

    // if exist upload and save to table
    $title = preg_replace('/[^a-z0-9]+/', '-', strtolower(Str::words($proposal->judul, 7, '')));
    $filename = $proposal->skema . '_' . $title . '_' . time() . '.pdf';

    $file = Storage::cloud()->putFileAs($request->input('id-folder'), $request->file('file'), $filename);
    $metadata = Storage::cloud()->getMetadata($file);
    $id_file = $metadata['path'];

    // simpan ke table review
    $review = $proposal->reviews()->create([
      'user_id' => auth()->user()->id,
      'type' => auth()->user()->roles->pluck('name')[0],
      'description' => $request->deskripsi,
      'file' => $id_file,
      'acc' => 1
    ]);

    // simpan ke table proposal
    $acc = $proposal->update([
      'file' => $id_file,
      'status' => 'selesai'
    ]);

    if ($review) {
      return ResponseFormatter::success($review, 'Data Berhasil disimpan', 201);
    } else {
      return ResponseFormatter::error(null, 'Data Gagal disimpan', 500);
    }
  }

  public function nilai(Request $request)
  {
    $this->validate($request, [
      'id-proposal' => 'required',
      'nilai' => 'required|numeric',
    ]);

    $proposal = Proposal::where('id', $request->input('id-proposal'));
    $tahun = $proposal->first()->period->tahun;
    $update = $proposal->update(['nilai' => $request->input('nilai')]);

    if ($update) {
      $proposals = DB::table('periods')
        ->join('proposals', 'periods.id', '=', 'proposals.period_id')
        ->select('periods.id as period_id', 'periods.tahun', 'proposals.id', 'proposals.skema', 'proposals.judul', 'proposals.status', 'proposals.nilai')
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
      ->select('periods.id as period_id', 'periods.tahun', 'proposals.id', 'proposals.skema', 'proposals.judul', 'proposals.status', 'proposals.nilai1', 'proposals.nilai2')
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

  public function download(Request $request)
  {
    // $proposal = Proposal::whereId($request->id)->first();
    $metadata = Storage::cloud()->getMetadata($request->file);
    $download = Storage::cloud()->download($request->file, $metadata['name']);
    return $download;
  }

  public function downloadForm(Request $request)
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

  public function downloadBerita(Request $request)
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
}
