<?php

namespace App\Services;

use App\Helpers\CloudStorage;
use App\Models\Proposal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminProposalService
{
    public function showAll($data)
    {
        $skema = $data['skema'];
        $proposals = DB::table('periods')
            ->join('proposals', 'periods.id', '=', 'proposals.period_id')
            ->select('periods.id as period_id', 'periods.tahun', 'proposals.id', 'proposals.skema', 'proposals.judul', 'proposals.status', 'proposals.nilai')
            ->where('periods.tahun', '=', $data['tahun'])
            ->when($skema, function ($q) use ($skema) {
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
        $reviewer = $data['reviewer'];
        $status = $data['status'];

        $proposal = Proposal::where('id', $proposal_id);
        $tahun = $proposal->first()->period->tahun;
        $update = $proposal->update(['status' => $status]);

        if ($update) {
            $reviewer = $proposal->first()
                ->teachers()->sync([
                        $pembimbing => ['jabatan' => 'Pembimbing'],
                        $reviewer => ['jabatan' => 'Reviewer']
                    ]);

            $proposals = DB::table('periods')
                ->join('proposals', 'periods.id', '=', 'proposals.period_id')
                ->select('periods.id as period_id', 'periods.tahun', 'proposals.id', 'proposals.skema', 'proposals.judul', 'proposals.status', 'proposals.nilai')
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
        $update = $proposal->update(['nilai' => $data['nilai']]);

        if ($update) {
            $proposals = DB::table('periods')
                ->join('proposals', 'periods.id', '=', 'proposals.period_id')
                ->select('periods.id as period_id', 'periods.tahun', 'proposals.id', 'proposals.skema', 'proposals.judul', 'proposals.status', 'proposals.nilai')
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

        if ($proposal->file) {
            Storage::cloud()->delete($proposal->file);
        } else {
            // if file not exist, delete from cloud storage
            $filePath = $proposal->file_path;
            if ($filePath) {
                CloudStorage::deleteFile($filePath);
            }
        }

        // delete in pivot table
        $pivot = DB::table('proposal_student')->where('proposal_id', $data['id'])->delete();
        // delete proposal
        $proposal = Proposal::destroy($data['id']);
        if ($proposal) {
            $proposals = DB::table('periods')
                ->join('proposals', 'periods.id', '=', 'proposals.period_id')
                ->select('periods.id as period_id', 'periods.tahun', 'proposals.id', 'proposals.skema', 'proposals.judul', 'proposals.status', 'proposals.nilai')
                ->where('periods.tahun', '=', $data['tahun'])
                ->get();
            return $proposals;
        } else {
            return false;
        }
    }
}
