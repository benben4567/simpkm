<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StudentImport implements ToCollection, WithMultipleSheets, WithHeadingRow
{
    private $rows = 0;

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
          $user = User::create([
            'name' => $row['nama'],
            'email' => $row['email'],
            'password' => Hash::make($row['nim']),
            'role' => 'student',
          ]);
          $user->student()->create([
            'major_id' => $row['id_prodi'],
            'nim' => $row['nim'],
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
