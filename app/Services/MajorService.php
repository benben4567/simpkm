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
            'jenjang' => $data['jenjang'],
            'nama' => $data['nama'],
            'kode_prodi' => $data['kode_prodi'],
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
            'jenjang' => $data['jenjang'],
            'nama' => $data['nama'],
            'kode_prodi' => $data['kode_prodi'],
        ]);

        return $major;
    }

    public function toggle($id)
    {
        $major = Major::find($id);
        $major->is_aktif = !$major->is_aktif;
        $major->save();

        return $major;
    }
}

