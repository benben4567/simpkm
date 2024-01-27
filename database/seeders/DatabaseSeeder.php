<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MajorSeeder::class);
        $this->call(RefSkemaSeeder::class);
        $this->call(UserSeeder::class);
    }
}
