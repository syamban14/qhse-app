<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Master\Permission;
use App\Models\Master\Role;

class MasterDataPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionName = 'manage-master-data';

        // Model App\Models\Master\Permission dan App\Models\Master\Role 
        // sudah dikonfigurasi untuk menggunakan koneksi 'pgsql_master',
        // jadi tidak perlu pengaturan koneksi tambahan di sini.

        // Buat permission jika belum ada
        $permission = Permission::firstOrCreate(
            ['name' => $permissionName, 'guard_name' => 'web']
        );

        $this->command->info('Permission "' . $permissionName . '" berhasil dibuat atau sudah ada.');

        // Cari role 'admin'
        try {
            $role = Role::findByName('admin', 'web');

            // Berikan permission ke role 'admin'
            if ($role->hasPermissionTo($permission)) {
                $this->command->info('Role "admin" sudah memiliki permission "' . $permissionName . '".');
            } else {
                $role->givePermissionTo($permission);
                $this->command->info('Permission "' . $permissionName . '" telah diberikan kepada role "admin".');
            }
        } catch (\Spatie\Permission\Exceptions\RoleDoesNotExist $e) {
            $this->command->error('Role "admin" tidak ditemukan. Izin tidak dapat diberikan.');
        }
    }
}