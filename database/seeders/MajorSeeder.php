<?php

namespace Database\Seeders;

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
      ['degree' => 'D3', 'name' => 'Keperawatan', 'kode_pddikti' => '14401'],
      ['degree' => 'D3', 'name' => 'Kebidanan', 'kode_pddikti' => '15401'],
      ['degree' => 'D3', 'name' => 'Akupunktur', 'kode_pddikti' => '11407'],
      ['degree' => 'D3', 'name' => 'Farmasi', 'kode_pddikti' => '48401'],
      ['degree' => 'D3', 'name' => 'RMIK', 'kode_pddikti' => '13462'],
      ['degree' => 'S1', 'name' => 'Kebidanan', 'kode_pddikti' => '15201'],
      ['degree' => 'S1', 'name' => 'Fisioterapi', 'kode_pddikti' => '11202'],
      ['degree' => 'S1', 'name' => 'Farmasi Klinis dan Komunitas', 'kode_pddikti' => '48202'],
      ['degree' => 'S1', 'name' => 'Informatika', 'kode_pddikti' => '55201'],
      ['degree' => 'S1', 'name' => 'Keperawatan', 'kode_pddikti' => '14201'],
      ['degree' => 'S1', 'name' => 'Keperawatan Anestesiologi', 'kode_pddikti' => '14320'],
      ['degree' => 'Profesi', 'name' => 'Bidan', 'kode_pddikti' => '15901'],
      ['degree' => 'Profesi', 'name' => 'Ners', 'kode_pddikti' => '14901'],
    ];

    foreach ($majors as $major) {
      \App\Models\Major::create($major);
    }
  }
}
