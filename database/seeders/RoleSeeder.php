<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar permissions berdasarkan module
        $permissions = [
            'view receive',
            'view keluhan',
            'view document',
            'view invoice',
            'view surat jalan',
            'view pembayaran',
            'view report',
        ];

        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        // Role: user
        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo([
            'view receive', 
            'view keluhan', 
            'view report'
        ]);

        // Role: admin
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'view document', 
            'view invoice',
            'view surat jalan', 
            'view report'
        ]);

        // Role: finance
        $finance = Role::create(['name' => 'finance']);
        $finance->givePermissionTo([
            'view pembayaran', 
            'view surat jalan', 
            'view invoice',
            'view report'
        ]);

        // Role: teknisi
        $teknisi = Role::create(['name' => 'teknisi']);
        $teknisi->givePermissionTo([
            'view keluhan', 
            'view report'
        ]);

        // Role: owner (super admin)
        $owner = Role::create(['name' => 'owner']);
        $owner->givePermissionTo(Permission::all()); // akses semua
    }
}
