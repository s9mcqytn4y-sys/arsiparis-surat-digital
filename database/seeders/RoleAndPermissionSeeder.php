<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Permissions
        $permissions = [
            'view_dashboard',
            'manage_unit_kerja',
            'manage_pegawai',
            'manage_master_nomor_surat',
            'manage_users',
            'view_surat_masuk',
            'create_surat_masuk',
            'edit_surat_masuk',
            'delete_surat_masuk',
            'disposisi_surat_masuk',
            'view_surat_keluar',
            'create_surat_keluar',
            'edit_surat_keluar',
            'delete_surat_keluar',
            'view_arsip_digital',
            'manage_arsip_digital',
            'export_reports',
            'view_activity_logs',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // 2. Define Roles
        $superAdmin = Role::findOrCreate('super_admin', 'web');
        $superAdmin->givePermissionTo(Permission::all());

        $petugasTu = Role::findOrCreate('petugas_tu', 'web');
        $petugasTu->givePermissionTo([
            'view_dashboard',
            'manage_pegawai',
            'view_surat_masuk',
            'create_surat_masuk',
            'edit_surat_masuk',
            'view_surat_keluar',
            'create_surat_keluar',
            'edit_surat_keluar',
            'view_arsip_digital',
            'manage_arsip_digital',
            'export_reports',
        ]);

        $pimpinanUnit = Role::findOrCreate('pimpinan_unit', 'web');
        $pimpinanUnit->givePermissionTo([
            'view_dashboard',
            'view_surat_masuk',
            'disposisi_surat_masuk',
            'view_surat_keluar',
            'view_arsip_digital',
            'export_reports',
        ]);
    }
}
