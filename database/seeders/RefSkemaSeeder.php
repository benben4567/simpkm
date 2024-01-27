<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RefSkemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          // PKM-RE
          // PKM-RSH
          // PKM-K
          // PKM-PM
          // PKM-PI
          // PKM-KC
          // PKM-KI
          // PKM-VGK
          // PKM-GFT
          // PKM-AI
        $data = [
            [
                'nama' => 'PKM-RE',
                'kepanjangan' => 'PKM Riset Eksakta',
                'is_aktif' => true,
            ],
            [
                'nama' => 'PKM-RSH',
                'kepanjangan' => 'PKM Riset Sosial Humaniora',
                'is_aktif' => true,
            ],
            [
                'nama' => 'PKM-K',
                'kepanjangan' => 'PKM Kewirausahaan',
                'is_aktif' => true,
            ],
            [
                'nama' => 'PKM-PM',
                'kepanjangan' => 'PKM Pengabdian Kepada Masyarakat',
                'is_aktif' => true,
            ],
            [
                'nama' => 'PKM-PI',
                'kepanjangan' => 'PKM Penerapan Iptek',
                'is_aktif' => true,
            ],
            [
                'nama' => 'PKM-KC',
                'kepanjangan' => 'PKM Karsa Cipta',
                'is_aktif' => true,
            ],
            [
                'nama' => 'PKM-KI',
                'kepanjangan' => 'PKM Karya Inovatif',
                'is_aktif' => true,
            ],
            [
                'nama' => 'PKM-VGK',
                'kepanjangan' => 'PKM Video Gagasan Konstruktif',
                'is_aktif' => true,
            ],
            [
                'nama' => 'PKM-GFT',
                'kepanjangan' => 'PKM Gagasan Futuristik Tertulis',
                'is_aktif' => true,
            ],
            [
                'nama' => 'PKM-AI',
                'kepanjangan' => 'PKM Artikel Ilmiah',
                'is_aktif' => true,
            ],
        ];
        
        
        foreach ($data as $key => $value) {
            \App\Models\RefSkema::create($value);
        }
    }
}
