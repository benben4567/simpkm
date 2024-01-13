<?php

namespace App\Services;

use App\Models\Major;

class MajorService
{
  public function showAll()
  {
    $majors = Major::all();

    return $majors;
  }

  public function store($data)
  {
    $major = Major::create([
      'degree' => $data['jenjang'],
      'name' => $data['nama'],
    ]);

    return $major;
  }

  public function show($id)
  {
    $major = Major::find($id);

    return $major;
  }

  public function update($data)
  {
    $major = Major::where('id', $data['id'])->update([
      'degree' => $data['jenjang'],
      'name' => $data['nama'],
    ]);

    return $major;
  }
}

