<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Réinitialiser le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        // ── Toutes les permissions ──
        $permissions = [
            // Plateforme
            'manage platform', 'manage users', 'assign roles', 'view analytics',
            // Tests
            'create test', 'edit test', 'publish test', 'pass test', 'view result',
            // Consultation
            'book consultation', 'manage consultation', 'conduct consultation',
            // Immigration
            'create program', 'apply program', 'review application', 'approve application',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // ── Rôles + permissions ──
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $admin      = Role::firstOrCreate(['name' => 'admin']);
        $instructor = Role::firstOrCreate(['name' => 'instructor']);
        $consultant = Role::firstOrCreate(['name' => 'consultant']);
        $student    = Role::firstOrCreate(['name' => 'student']);
        $partner    = Role::firstOrCreate(['name' => 'partner']);
        $user    = Role::firstOrCreate(['name' => 'user']);

        $superAdmin->givePermissionTo(Permission::all());

        $admin->givePermissionTo([
            'manage users', 'view analytics',
            'create test', 'edit test', 'publish test', 'view result',
            'manage consultation',
        ]);

        $instructor->givePermissionTo([
            'create test', 'edit test', 'publish test', 'view result',
        ]);

        $consultant->givePermissionTo([
            'manage consultation', 'conduct consultation',
            'review application',
        ]);

        $student->givePermissionTo([
            'pass test', 'view result',
            'book consultation',
            'apply program',
        ]);

        $partner->givePermissionTo([
            'create program', 'review application', 'approve application',
        ]);

        $user->givePermissionTo([
            'pass test',
            'view result',
            'book consultation',
        ]);
    }
}