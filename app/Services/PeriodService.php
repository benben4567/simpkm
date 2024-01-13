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
    $dir = Storage::cloud()->makeDirectory($year);
    if ($dir) {
      $contents = collect(Storage::cloud()->listContents('/', false));
      $dir = $contents->where('type', '=', 'dir')
        ->where('filename', '=', $year)
        ->first();
      // get directory id
      $id_directory = $dir['path'];
    }

    $dir2 = Storage::cloud()->makeDirectory('review_' . $year);
    if ($dir) {
      $contents = collect(Storage::cloud()->listContents('/', false));
      $dir = $contents->where('type', '=', 'dir')
        ->where('filename', '=', 'review_' . $year)
        ->first();
      // get directory id
      $id_directory_review = $dir['path'];
    }

    $period = Period::create([
      'tahun' => $data['tahun'],
      'id_folder' => $id_directory,
      'id_folder_review' => $id_directory_review
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

