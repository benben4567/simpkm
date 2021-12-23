<?php

namespace App\Services;

use App\Proposal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminProposalService
{
  public function showAll($data)
  {
    $skema = $data['skema'];
    $proposals = DB::table('periods')
                ->join('proposals', 'periods.id', '=', 'proposals.period_id')
                ->select('periods.id as period_id', 'periods.tahun', 'proposals.id', 'proposals.skema', 'proposals.judul', 'proposals.status', 'proposals.nilai1', 'proposals.nilai2')
                ->where('periods.tahun', '=', $data['tahun'])
                ->when($skema, function($q) use ($skema) {
                  return $q->where('skema', '=', $skema);
                })
                ->get();

    return $proposals;
  }

  public function show($id)
  {
    $proposal = Proposal::with('teachers')->whereId($id)->first();
    return $proposal;
  }

  public function update($data)
  {
    $proposal_id = $data['id'];
    $pembimbing = $data['pembimbing'];
    $reviewer1 = $data['reviewer_1'];
    $reviewer2 = $data['reviewer_2'];
    $status = $data['status'];

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
                    ->select('periods.id as period_id', 'periods.tahun', 'proposals.id', 'proposals.skema', 'proposals.judul', 'proposals.status',  'proposals.nilai1', 'proposals.nilai2')
                    ->where('periods.tahun', '=', $tahun)
                    ->get();
    } else {
      return false;
    }

    return $proposals;
  }

  public function nilai($data)
  {
    $proposal = Proposal::where('id', $data['id-proposal']);
    $tahun = $proposal->first()->period->tahun;
    $update = $proposal->update(['nilai1' => $data['nilai1'], 'nilai2' => $data['nilai2']]);

    if($update) {
      $proposals = DB::table('periods')
                      ->join('proposals', 'periods.id', '=', 'proposals.period_id')
                      ->select('periods.id as period_id', 'periods.tahun', 'proposals.id', 'proposals.skema', 'proposals.judul', 'proposals.status', 'proposals.nilai1', 'proposals.nilai2')
                      ->where('periods.tahun', '=', $tahun)
                      ->get();
    } else {
      return false;
    }

    return $proposals;
  }

  public function delete($data)
  {
    $proposal = Proposal::where('id', $data['id'])->first();

    // delete file
    $proposal = Proposal::whereId($data['id'])->first();
    Storage::delete('public/files/'.$proposal->file);

    // delete proposal
    $proposal = Proposal::destroy($data['id']);
    if ($proposal) {
      $proposals = DB::table('periods')
                    ->join('proposals', 'periods.id', '=', 'proposals.period_id')
                    ->select('periods.id as period_id', 'periods.tahun', 'proposals.id', 'proposals.skema', 'proposals.judul', 'proposals.status',  'proposals.nilai1', 'proposals.nilai2')
                    ->where('periods.tahun', '=', $data['tahun'])
                    ->get();
      return $proposals;
    } else {
      return false;
    }
  }
}
