<?php

namespace Database\Seeders;

use App\Models\Master\Driver;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SyncDriverUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Synchronizing users for all drivers...');

        // Get all drivers with their employee data
        $drivers = Driver::with('karyawan')->get();

        foreach ($drivers as $driver) {
            if (!$driver->karyawan) {
                $this->command->warn("Skipping driver with ID: {$driver->id} because it has no associated karyawan record.");
                continue;
            }

            $karyawan = $driver->karyawan;
            $payrollId = $karyawan->payroll_id;
            
            if (empty($payrollId)) {
                $this->command->warn("Skipping karyawan: {$karyawan->nama_karyawan} (ID: {$karyawan->id}) due to empty payroll_id.");
                continue;
            }

            // Use firstOrCreate to prevent creating duplicate users
            $user = User::firstOrCreate(
                ['payroll_id' => $payrollId],
                [
                    'name' => $karyawan->nama_karyawan,
                    'email' => strtolower($payrollId) . '@corp.solid.co.id',
                    'password' => Hash::make(Str::random(10)), // A secure default password
                    'is_active' => true,
                ]
            );

            if ($user->wasRecentlyCreated) {
                $this->command->info("Created new user for: {$karyawan->nama_karyawan} (Payroll ID: {$payrollId})");
            } else {
                $this->command->line("User already exists for: {$karyawan->nama_karyawan} (Payroll ID: {$payrollId})");
            }
        }

        $this->command->info('Driver user synchronization completed.');
    }
}