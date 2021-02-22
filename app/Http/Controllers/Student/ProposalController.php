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
use Codedge\Fpdf\Facades\Fpdf;

class ProposalController extends Controller
{

    public function index()
    {
      $student = Student::with('proposals')->whereId(Auth::user()->student->id)->first();
      return view('pages.student.proposal', compact('student'));
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
      $periode = Period::where('status', '=', 'buka')->first();
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
        // Upload file Update
        if($request->file('file')) {
          $fileName = time().'.'.$request->file('file')->extension();
          $path = $request->file('file')->storeAs(
            'public/files', $fileName
          );
        }

        // Select periode
        $periode = Period::where('tahun', '=', $request->input('tahun'))->first();
        // Store Proposal

        $proposal = Proposal::create([
          'period_id' => $periode->id,
          'skema' => $request->input('skema'),
          'judul' => $request->input('judul'),
          'status' => 'kompilasi',
          'file' => $fileName
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
        'skema' => 'required',
        'dosen' => 'required',
        'judul' => 'required',
        'file' => 'nullable|mimes:pdf|max:2048'
      ]);

      // DB Transaction
      DB::transaction(function () use ($request) {
        // Upload file Update
        if($request->file('file')) {
          $fileName = time().'.'.$request->file('file')->extension();
          $path = $request->file('file')->storeAs(
            'public/files', $fileName
          );
        }

        if ($request->file('file')) {
          $proposal = Proposal::whereId($request->input('id'))->update([
            'file' => $fileName
          ]);
        }

        // Update Proposal
        $proposal = Proposal::whereId($request->input('id'))->update([
          'skema' => $request->input('skema'),
          'judul' => $request->input('judul'),
          'status' => 'kompilasi',
        ]);

        // Attach Teacher
        $proposal = Proposal::find($request->input('id'));
        $proposal->teachers()->sync($request->input('dosen'), ['jabatan' => 'Pembimbing']);
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
        '[NIDN_REVIEWER1]' => $proposal->reviewer1->first()->nidn,
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
}
