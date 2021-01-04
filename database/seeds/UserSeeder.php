<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User Admin
        User::create([
            'email' => 'admin@simpkm.com',
            'name' => 'Admin',
            'password' => Hash::make('123456'),
            'role' => 'admin'
        ]);

        // User w/ Student Role
        $student = User::create([
          'email' => 'student@simpkm.com',
          'name' => 'Student',
          'password' => Hash::make('123456'),
          'role' => 'student'
        ]);

        // Student
        $student->student()->create([
          'nim' => '111111',
          'nama' => 'Student',
        ]);
    }
}
