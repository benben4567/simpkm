<?php

namespace App\Http\Controllers\Student;

use App\Helpers\CloudStorage;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Jobs\UploadProposal;
use App\Models\RefSkema;
use Illuminate\Http\Request;
use App\Models\Period;
use App\Models\Teacher;
use App\Models\Proposal;
use App\Models\Review;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Codedge\Fpdf\Facades\Fpdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ProposalController extends Controller
{

    public function index(Request $request)
    {
        $periods = Period::all()->sortByDesc('tahun');
        $now = collect($periods->first());
        $now->put('hash', Crypt::encryptString($now['id']));

        if ($now) {
            if ($request->input('periode')) {
                $periode = $request->input('periode');

                $now = Period::where('id', $periode)->first();
                $now = collect($now);
                $now->put('hash', Crypt::encryptString($now['id']));

                $student = Student::with([
                    'proposals' => function ($q) use ($now) {
                        $q->where('period_id', $now['id']);
                    }
                ])->whereId(Auth::user()->student->id)->first();

                return view('pages.student.proposal', compact('student', 'periods', 'now', 'periode'));
            } else {
                $student = Student::with([
                    'proposals' => function ($q) use ($now) {
                        $q->where('period_id', $now['id']);
                    }
                ])->whereId(Auth::user()->student->id)->first();
            }
        } else {
            $student = Student::whereId(Auth::user()->student->id)->first();
        }


        return view('pages.student.proposal', compact('student', 'periods', 'now'));
    }

    public function show($id)
    {
        $proposal = Proposal::findOrFail($id);
        $members = $proposal->students;
        $pembimbing = $proposal->pembimbing->first();
        $reviewer = $proposal->reviewer->first();
        return view('pages.student.proposal_show', compact('proposal', 'members', 'pembimbing', 'reviewer'));
    }

    public function create(Request $request)
    {
        $id_periode = Crypt::decryptString($request->input('periode'));

        $periode = Period::where('id', $id_periode)->first();
        $teachers = Teacher::all();
        $skema = RefSkema::where('is_aktif', true)->get();
        return view('pages.student.proposal_create', compact('periode', 'teachers', 'skema'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'tahun' => 'required',
            'skema' => 'required',
            'dosen' => 'required',
            'judul' => 'required',
            'file' => 'required|mimes:pdf|max:2048'
        ]);

            DB::transaction(function () use ($request) {
                $periode = Period::where('tahun', $request->input('tahun'))->firstOrFail();

                $title = preg_replace('/[^a-z0-9]+/', '-', strtolower(Str::words($request->input('judul'), 7, '')));
                $filename = $request->input('skema') . '_' . $title . '_' . Str::random(7) . '.pdf';

                $tempFilename = Str::random(10) . '.pdf';
                $tempPath = storage_path('app/temp_proposal/' . $tempFilename);

                $request->file('file')->move(storage_path('app/temp_proposal'), $tempFilename);


                // Simpan ke table proposal
                $proposal = Proposal::create([
                    'period_id' => $periode->id,
                    'skema' => $request->input('skema'),
                    'judul' => $request->input('judul'),
                    'status' => 'kompilasi',
                ]);

                $review = $proposal->reviews()->create([
                    'user_id' => auth()->user()->id,
                    'type' => auth()->user()->roles->pluck('name')[0],
                    'description' => 'Usulan Proposal Awal',
                ]);

                // Dispatch job dengan hanya mengirim path (bukan konten)
                UploadProposal::dispatch($tempPath, $filename, $periode->tahun, $review->id);
                // Attach Student
                $proposal->students()->attach(auth()->user()->student->id, ['jabatan' => 'Ketua']);

                // Attach Teacher
                $proposal->teachers()->attach($request->input('dosen'), ['jabatan' => 'Pembimbing']);
            });
        try {

            return redirect()->route('proposal.index')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('proposal.index')->with('error', 'Data gagal disimpan');
        }
    }


    public function edit($id)
    {
        $proposal = Proposal::findOrFail($id);
        $student = $proposal->students->first();
        $pembimbing = $proposal->teachers->first();
        $teachers = Teacher::all();
        if ($student->id == Auth::user()->student->id) {
            return view('pages.student.proposal_edit', compact('proposal', 'pembimbing', 'teachers'));
        } else {
            return abort(404);
        }
    }

    public function update(Request $request)
    {

        $proposal = Proposal::find($request->input('id'));

        if ($proposal->status == 'kompilasi') {
            $validator = [
                'id' => 'required',
                'judul' => 'required',
                'file' => 'required|mimes:pdf|max:2048'
            ];
        } else {
            $validator = [
                'id' => 'required',
                'judul' => 'required',
            ];
        }

        $this->validate($request, $validator);

        // DB Transaction
        DB::transaction(function () use ($request) {
            // Upload file Update
            if ($request->file('file')) {
                // get file id
                $proposal = Proposal::whereId($request->input('id'))->first();
                $id_file = $proposal->file;

                // filename
                $title = preg_replace('/[^a-z0-9]+/', '-', strtolower(Str::words($request->input('judul'), 7, '')));
                $filename = $proposal->skema . '_' . $title . '_' . Str::random(7);

                // if exist delete then upload to directory and save id to table
                if ($proposal->file_path && $proposal->file_url) {
                    // delete old file
                    CloudStorage::deleteFile($proposal->file_path);

                    // upload new file
                    $dirname = $proposal->period->tahun . '/proposal';
                    $file = CloudStorage::upload($dirname, $request->file('file'), $filename);
                } else {
                    // upload new file
                    $dirname = $proposal->period->tahun . '/proposal';
                    $file = CloudStorage::upload($dirname, $request->file('file'), $filename);
                }

                // review
                $review = Review::where('proposal_id', $request->input('id'))->first();
                if ($review) {
                    // if exist delete then upload to directory and save id to table
                    if ($review->file_path && $review->file_url) {
                        // delete old file
                        CloudStorage::deleteFile($review->file_path);

                        // upload new file
                        $dirname = $proposal->period->tahun . '/review';
                        $file = CloudStorage::upload($dirname, $request->file('file'), $filename);
                    } else {
                        // upload new file
                        $dirname = $proposal->period->tahun . '/review';
                        $file = CloudStorage::upload($dirname, $request->file('file'), $filename);
                    }
                }

                $review = Review::where('proposal_id', $request->input('id'))->update([
                    'file_path' => $file['path'],
                    'file_url' => $file['url'],
                ]);

                $proposal = Proposal::whereId($request->input('id'))->update([
                    'file_path' => $file['path'],
                    'file_url' => $file['url'],
                ]);

            }

            $proposal = Proposal::whereId($request->input('id'))->update([
                'judul' => $request->input('judul'),
            ]);
        });

        return redirect()->route('proposal.index')->with('success', 'Data berhasil diupdate');
    }

    public function member($id)
    {
        $proposal = Proposal::findOrFail($id);
        $members = $proposal->students;
        $members_id = $proposal->students->pluck('id')->toArray();
        $students = Student::whereNotIn('id', $members_id)->get();
        $pembimbing = $proposal->pembimbing->first();
        $reviewer = $proposal->reviewer->first();
        $ketua = $proposal->ketua->first();

        return view('pages.student.proposal_member', compact('proposal', 'members', 'students', 'pembimbing', 'reviewer', 'ketua'));

        if ($members->first()->id == Auth::user()->student->id) {
        } else {
            return abort(404);
        }
    }

    public function memberAdd(Request $request)
    {
        $proposal_id = $request->input('proposal');
        $student_id = $request->input('student');

        $proposal = Proposal::find($proposal_id);
        $proposal->students()->attach($student_id, ['jabatan' => 'Anggota']);

        return response()->json([
            'success' => true,
            'msg' => 'Anggota telah ditambahkan'
        ]);
    }

    public function memberRemove(Request $request)
    {
        $proposal_id = $request->input('proposal');
        $student_id = $request->input('student');

        $proposal = Proposal::find($proposal_id);
        $proposal->students()->detach($student_id);

        return response()->json([
            'success' => true,
            'msg' => 'Anggota telah dihapus'
        ]);
    }

    public function review($id)
    {
        $proposal = Proposal::whereId($id)->with('reviews.user')->first();
        $periode = $proposal->period->first();
        $ketua = $proposal->ketua->first();
        $pembimbing = $proposal->pembimbing->first();
        $reviewer = $proposal->reviewer->first();
        $anggota = $proposal->anggota->toArray();



        return view('pages.student.review', compact('proposal', 'periode', 'ketua', 'pembimbing', 'reviewer', 'anggota'));
    }

    public function reviewStore(Request $request)
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
            'file' => $id_file
        ]);

        if ($review) {
            return ResponseFormatter::success($review, 'Data Berhasil disimpan', 201);
        } else {
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
            '[REVIEWER1]' => $proposal->reviewer1->first()->nama,
            '[REVIEWER2]' => $proposal->reviewer2->first()->nama
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
            '[REVIEWER1]' => $proposal->reviewer1->first()->nama,
            '[REVIEWER2]' => $proposal->reviewer2->first()->nama,
            '[NIDNREVIEWER1]' => $proposal->reviewer1->first()->nidn,
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
        $nama_file = 'BA_' . $skema . '_' . $rand . '.doc';

        return \WordTemplate::export($file, $data, $nama_file);
    }

    public function exportForm($data, $skema)
    {
        $file = asset('template/' . $skema . '.rtf');

        $rand = uniqid();
        $nama_file = 'FORM_' . $skema . '_' . $rand . '.doc';

        return \WordTemplate::export($file, $data, $nama_file);
    }

    public function destroy(Request $request)
    {
        $proposal = Proposal::where('id', $request->input('id'))->first();
        $status = $proposal->period->status;
        // cek apakah periode terkait ditutup atau dibuka.
        if ($status == 'tutup') {
            return response()->json([
                'success' => false,
                'msg' => 'Usulan tidak dapat dihapus karena periode sudah ditutup.'
            ], 200);
        }

        // delete file
        $proposal = Proposal::whereId($request->input('id'))->first();
        $file = $proposal->reviews()->first();
        Storage::cloud()->delete($file->file);

        // delete in pivot table
        $pivot = DB::table('proposal_student')->where('proposal_id', $request->input('id'))->delete();
        // delete proposal
        $proposal = Proposal::destroy($request->input('id'));

        return redirect()->back();
    }
}
