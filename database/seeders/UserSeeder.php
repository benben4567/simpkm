<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

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
        $admin = User::create([
            'email' => 'admin@email.test',
            'username' => 'admin',
            'name' => 'Admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole('admin');

        $permissions = Permission::get()->pluck('name')->toArray();
        $admin->syncPermissions($permissions);

        // User w/ Student Role
        $student = User::create([
            'email' => 'student@email.test',
            'username' => '111111',
            'name' => 'Student',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $student->assignRole('student');

        // Student
        $student->student()->create([
            'nim' => '111111',
            'nama' => 'Student',
        ]);

        // User w/ Teacher Role
        $teacher = User::create([
            'email' => 'teacher@email.test',
            'username' => 'teacher',
            'name' => 'Teacher',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $teacher->assignRole('teacher');

        // Teacher
        $teacher->teacher()->create([
            'major_id' => 1,
            'nidn' => '111111',
            'nama' => 'Teacher',
            'jk' => 'laki',
            'tempat_lahir' => 'Bandung',
            'tgl_lahir' => '1980-01-01',
            'no_hp' => '081234567890',
        ]);
    }
}
