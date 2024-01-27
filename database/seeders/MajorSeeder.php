<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $majors = [
      ['jenjang' => 'D3', 'nama' => 'Keperawatan', 'kode_prodi' => '14401'],
      ['jenjang' => 'D3', 'nama' => 'Kebidanan', 'kode_prodi' => '15401'],
      ['jenjang' => 'D3', 'nama' => 'Akupunktur', 'kode_prodi' => '11407'],
      ['jenjang' => 'D3', 'nama' => 'Farmasi', 'kode_prodi' => '48401'],
      ['jenjang' => 'D3', 'nama' => 'RMIK', 'kode_prodi' => '13462'],
      ['jenjang' => 'S1', 'nama' => 'Kebidanan', 'kode_prodi' => '15201'],
      ['jenjang' => 'S1', 'nama' => 'Fisioterapi', 'kode_prodi' => '11202'],
      ['jenjang' => 'S1', 'nama' => 'Farmasi Klinis dan Komunitas', 'kode_prodi' => '48202'],
      ['jenjang' => 'S1', 'nama' => 'Informatika', 'kode_prodi' => '55201'],
      ['jenjang' => 'S1', 'nama' => 'Keperawatan', 'kode_prodi' => '14201'],
      ['jenjang' => 'S1', 'nama' => 'Keperawatan Anestesiologi', 'kode_prodi' => '14320'],
      ['jenjang' => 'Profesi', 'nama' => 'Bidan', 'kode_prodi' => '15901'],
      ['jenjang' => 'Profesi', 'nama' => 'Ners', 'kode_prodi' => '14901'],
    ];

    foreach ($majors as $major) {
      Major::create($major);
    }
  }
}
