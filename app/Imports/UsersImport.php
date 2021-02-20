<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    private $rows = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
          $teacher = User::create([
            'name' => $row['nama'],
            'email' => $row['email'],
            'password' => Hash::make($row['nidn']),
            'role' => $row['role'],
          ]);
          $teacher->teacher()->create([
            'major_id' => $row['id_prodi'],
            'nidn' => $row['nidn'],
            'nama' => $row['nama'],
          ]);
          ++$this->rows;
        }
    }

    public function getRowCount(): int
    {
      return $this->rows;
    }
}
