<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $data = ['admin', 'student', 'teacher'];
        foreach ($data as $key => $value) {
            Role::updateOrCreate([
                'name' => $value,
            ]);
        }
    }
}
