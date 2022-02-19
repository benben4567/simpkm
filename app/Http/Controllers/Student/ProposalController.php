<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Period;
use App\Teacher;
use App\Proposal;
use App\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Codedge\Fpdf\Facades\Fpdf;

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
          $student = Student::with(['proposals' => function($q) use ($now) {
                        $q->where('period_id', $now->id);
                      }])->whereId(Auth::user()->student->id)->first();


          return view('pages.student.proposal', compact('student', 'periods', 'now', 'periode'));

          // return redirect()->back()->withInput()->with([
          //   'student' => $student,
          //   'periods' => $periods,
          //   'now' => $now
          // ]);

        } else {
          $student = Student::with(['proposals' => function($q) use ($now) {
                      $q->where('period_id', $now->id);
                    }])->whereId(Auth::user()->student->id)->first();
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
      $reviewer1 = $proposal->reviewer1->first();
      $reviewer2 = $proposal->reviewer2->first();
      return view('pages.student.proposal_show', compact('proposal','members', 'pembimbing', 'reviewer1', 'reviewer2'));
    }

    public function create()
    {
      $periode = Period::all()->sortByDesc('tahun')->first();
      $teachers = Teacher::all();
      return view('pages.student.proposal_create', compact('periode','teachers'));
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

      // DB Transaction
      DB::transaction(function () use ($request) {

        // Upload file
        if($request->file('file')) {
          $title = preg_replace( '/[^a-z0-9]+/', '-', strtolower(Str::words($request->input('judul'), 7, '')));
          $filename = $request->input('skema').'_'.$title.'_'.Str::random(7).'.pdf';

          // get folder id by tahun
          $periode = Period::where('tahun', '=', $request->input('tahun'))->first();
          // if exist upload and save to table
          if ($periode->id_folder) {
            $file = Storage::cloud()->putFileAs($periode->id_folder, $request->file('file'), $filename);
            $metadata = Storage::cloud()->getMetadata($file);
            $id_file = $metadata['path'];
          } else {
            // create directory
            $year = $periode->tahun;
            $dir = Storage::cloud()->makeDirectory($year);
            if ($dir) {
              $contents = collect(Storage::cloud()->listContents('/', false));
              $dir = $contents->where('type', '=', 'dir')
                  ->where('filename', '=', $year)
                  ->first();
              // get directory id
              $id_directory = $dir['path'];
            }
            // upload file
            $file = Storage::cloud()->putFileAs($id_directory, $request->file('file'), $filename);
            $metadata = Storage::cloud()->getMetadata($file);
            $id_file = $metadata['path'];
          }
        }

        $proposal = Proposal::create([
          'period_id' => $periode->id,
          'skema' => $request->input('skema'),
          'judul' => $request->input('judul'),
          'status' => 'kompilasi',
          'file' => $id_file
        ]);

        // Attach Student
        $proposal->students()->attach(Auth::user()->student->id, ['jabatan' => 'Ketua']);
        // Attach Teacher
        $proposal->teachers()->attach($request->input('dosen'), ['jabatan' => 'Pembimbing']);
      });

      return redirect()->route('proposal.index')->with('success', 'Data berhasil disimpan');
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
      $this->validate($request, [
        'id' => 'required',
        'judul' => 'required',
        'file' => 'required|mimes:pdf|max:2048'
      ]);

      // DB Transaction
      DB::transaction(function () use ($request) {
        // Upload file Update
        if($request->file('file')) {
          // get file id
          $proposal = Proposal::whereId($request->input('id'))->first();
          $id_file = $proposal->file;

          // get directory id
          $id_directory = Period::where('id', '=', $proposal->period_id)->first()->id_folder;

          // filename
          $title = preg_replace( '/[^a-z0-9]+/', '-', strtolower(Str::words($request->input('judul'), 7, '')));
          $filename = $proposal->skema.'_'.$title.'_'.Str::random(7).'.pdf';

          // if exist delete then upload to directory and save id to table
          $exist = Storage::cloud()->exists($id_file);
          if ($exist) {
            // delete old file
            Storage::cloud()->delete($id_file);
            // upload new file
            $file = Storage::cloud()->putFileAs($id_directory, $request->file('file'), $filename);
            $metadata = Storage::cloud()->getMetadata($file);
            $id_file = $metadata['path'];
          } else {
            // upload new file
            $file = Storage::cloud()->putFileAs($id_directory, $request->file('file'), $filename);
            $metadata = Storage::cloud()->getMetadata($file);
            $id_file = $metadata['path'];
          }
        }

        // Update Table Proposal
        $proposal = Proposal::whereId($request->input('id'))->update([
          'judul' => $request->input('judul'),
          'file' => $id_file
        ]);
      });

      return redirect()->route('proposal.index')->with('success','Data berhasil diupdate');
    }

    public function member($id)
    {
      $proposal = Proposal::findOrFail($id);
      $members = $proposal->students;
      $members_id = $proposal->students->pluck('id')->toArray();
      $students = Student::whereNotIn('id', $members_id)->get();
      $pembimbing = $proposal->pembimbing->first();
      $reviewer1 = $proposal->reviewer1->first();
      $reviewer2 = $proposal->reviewer2->first();
      if ($members->first()->id == Auth::user()->student->id) {
        return view('pages.student.proposal_member', compact('proposal','members', 'students', 'pembimbing', 'reviewer1', 'reviewer2'));
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

    public function downloadProposal(Request $request)
    {
      $proposal = Proposal::whereId($request->id)->first();
      $metadata = Storage::cloud()->getMetadata($proposal->file);
      $download = Storage::cloud()->download($proposal->file, $metadata['name']);
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
      $nama_file = 'BA_'.$skema.'_'.$rand.'.doc';

      return \WordTemplate::export($file, $data, $nama_file);
    }

    public function exportForm($data,$skema)
    {
      $file = asset('template/'.$skema.'.rtf');

      $rand = uniqid();
      $nama_file = 'FORM_'.$skema.'_'.$rand.'.doc';

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
      Storage::delete('public/files/'.$proposal->file);

      // delete proposal
      $proposal = Proposal::destroy($request->input('id'));
      return redirect()->back();
    }
}
