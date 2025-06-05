<?php

namespace App\Services;

use App\Models\Period;
use Illuminate\Support\Facades\Storage;

class PeriodService
{
    public function showAll()
    {
        $periods = Period::withCount('proposals')->get();
        return $periods;
    }

    public function store($data)
    {

        // buat folder proposal di google drive
        $year = $data['tahun'];
        $period = Period::create([
            'tahun' => $data['tahun'],
        ]);

        return $period;
    }

    public function show($data)
    {
        $period = Period::whereId($data['id'])->first();

        return $period;
    }

    public function update($data)
    {


        // update status
        if ($data['status'] == 'aktif') {
            // cek apakah ada periode lain yang aktif
            $period = Period::where('id', '!=', $data['id'])->where('status', '=', 'aktif')->count();
            if (!$period) {
                $period = Period::where('id', '=', $data['id'])->update(['status' => 'aktif']);
            } else {
                $period = false;
            }

            // update pendaftaran
            $pendaftaran = Period::whereId($data['id'])->update(['pendaftaran' => $data['pendaftaran']]);

        } else {
            $period = Period::where('id', '=', $data['id'])->update([
                'status' => 'nonaktif',
                'pendaftaran' => 'tutup',
            ]);
        }

        return $period;
    }
}

