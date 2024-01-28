<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'management-user',
            'management-role',
            'management-permission',
            'management-skema',
            'management-prodi',
            'monitoring-database',
            'monitoring-error',
            'monitoring-queue',
            // 'list-personil',
            // 'insert-personil',
            // 'update-personil',
            // 'show-personil',
            // 'import-personil',
        ];
        
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }
    }
}
