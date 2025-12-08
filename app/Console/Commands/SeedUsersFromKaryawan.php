<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon; // For timestamps
use Illuminate\Support\Str;

class SeedUsersFromKaryawan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-users-from-karyawan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeds the users table from m_karyawan data and adds an admin user.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to seed users table from m_karyawan data...');

        $now = Carbon::now();

        // Admin User Details
        $adminName = 'Admin';
        $adminEmail = 'admin@bcs-logistics.co.id';

        // --- Add Admin User ---
        $this->info("Adding admin user: {$adminEmail}");
        $adminExists = DB::connection('pgsql_master')->table('users')->where('email', $adminEmail)->exists();

        if ($adminExists) {
            $this->warn('Admin user already exists. Skipping insertion.');
        } else {
            DB::connection('pgsql_master')->table('users')->insert([
                'name' => $adminName,
                'email' => $adminEmail,
                'payroll_id' => 'ADMIN-001', // Placeholder payroll_id for admin
                'password' => Hash::make(Str::random(10)),
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $this->info('Admin user added successfully.');
        }

        // --- Add Karyawan Users ---
        $this->info('Fetching m_karyawan data...');
        $karyawanData = DB::connection('pgsql_master')->table('m_karyawan')
                            ->select('nama_karyawan', 'email', 'payroll_id')
                            ->whereNotNull('email') // Only process karyawan with an email
                            ->where('email', '!=', $adminEmail) // Exclude admin email if it's in m_karyawan
                            ->get();

        $insertedCount = 0;
        foreach ($karyawanData as $karyawan) {
            $userExists = DB::connection('pgsql_master')->table('users')->where('email', $karyawan->email)->exists();

            if ($userExists) {
                $this->warn("User with email {$karyawan->email} already exists. Skipping.");
            } else {
                DB::connection('pgsql_master')->table('users')->insert([
                    'name' => $karyawan->nama_karyawan,
                    'email' => $karyawan->email,
                    'payroll_id' => $karyawan->payroll_id,
                    'password' => Hash::make(Str::random(10)),
                    'email_verified_at' => $now, // Assuming email is verified for seeded users
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $insertedCount++;
            }
        }

        $this->info("Finished seeding. {$insertedCount} karyawan users added.");
        $this->info("Total users in master_db: " . DB::connection('pgsql_master')->table('users')->count());
    }
}