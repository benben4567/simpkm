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
          if ($row['role'] == 'student') {
            // student
            $user = User::create([
              'name' => $row['nama'],
              'email' => $row['email'],
              'password' => Hash::make($row['nim']),
              'role' => $row['role'],
            ]);
            $user->student()->create([
              'major_id' => $row['id_prodi'],
              'nim' => $row['nim'],
              'nama' => $row['nama'],
            ]);
          } else {
            // teacher
            $user = User::create([
              'name' => $row['nama'],
              'email' => $row['email'],
              'password' => Hash::make($row['nidn']),
              'role' => $row['role'],
            ]);
            $user->teacher()->create([
              'major_id' => $row['id_prodi'],
              'nidn' => $row['nidn'],
              'nama' => $row['nama'],
            ]);
          }
          ++$this->rows;
        }
    }

    public function getRowCount(): int
    {
      return $this->rows;
    }
}
