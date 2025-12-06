<?php

namespace Database\Seeders;

use App\Models\Master\Karyawan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyDriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- Create a Dumptruck/Trailer Driver ---
        $dumptruckDriver = Karyawan::firstOrCreate(
            ['payroll_id' => 'DUMMY-001'],
            [
                'nama_karyawan' => 'Budi Dumptruck',
                'title' => 'DRIVER',
                'email' => 'budi.dumptruck@example.com',
                'aktif' => true,
                'tgl_masuk' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'budi.dumptruck@example.com'],
            [
                'karyawan_id' => $dumptruckDriver->id,
                'password' => Hash::make(Str::random(10)),
                'is_active' => true,
            ]
        );

        // --- Create a Project Driver ---
        $projectDriver = Karyawan::firstOrCreate(
            ['payroll_id' => 'DUMMY-002'],
            [
                'nama_karyawan' => 'Citra Project',
                'title' => 'DRIVER PROJECT',
                'email' => 'citra.project@example.com',
                'aktif' => true,
                'tgl_masuk' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'citra.project@example.com'],
            [
                'karyawan_id' => $projectDriver->id,
                'password' => Hash::make(Str::random(10)),
                'is_active' => true,
            ]
        );

        // --- Create another Dumptruck/Trailer Driver ---
        $trailerDriver = Karyawan::firstOrCreate(
            ['payroll_id' => 'DUMMY-003'],
            [
                'nama_karyawan' => 'Dedi Trailer',
                'title' => 'DRIVER',
                'email' => 'dedi.trailer@example.com',
                'aktif' => true,
                'tgl_masuk' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'dedi.trailer@example.com'],
            [
                'karyawan_id' => $trailerDriver->id,
                'password' => Hash::make(Str::random(10)),
                'is_active' => true,
            ]
        );

        $this->command->info('Dummy driver data seeded successfully!');
    }
}
