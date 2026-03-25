<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'manage users',
            'create test',
            'pass test',
            'book consultation',
            'apply program'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name'=>$permission]);
        }

        $admin = Role::firstOrCreate(['name'=>'admin']);
        $student = Role::firstOrCreate(['name'=>'student']);

        $admin->givePermissionTo(Permission::all());

        $student->givePermissionTo([
            'pass test',
            'book consultation',
            'apply program'
        ]);
    }
}