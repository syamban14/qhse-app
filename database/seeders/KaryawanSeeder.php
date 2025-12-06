<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\Master\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = base_path('database/seeders/data/m_karyawan.csv');

        if (!file_exists($csvFile)) {
            $csvFile = 'dbs/master/csv/m_karyawan.csv';
            if(!file_exists($csvFile)) {
                $this->command->error("CSV file not found in primary or fallback path.");
                return;
            }
        }

        $file = fopen($csvFile, 'r');
        if ($file === false) {
            $this->command->error("Could not open CSV file: {$csvFile}");
            return;
        }

        $dbColumns = Schema::connection('pgsql_master')->getColumnListing('m_karyawan');
        $csvHeader = fgetcsv($file);
        if ($csvHeader === false) {
            $this->command->error("Could not read header from CSV file: {$csvFile}");
            fclose($file);
            return;
        }

        if (substr($csvHeader[0], 0, 3) == "\xef\xbb\xbf") {
            $csvHeader[0] = substr($csvHeader[0], 3);
        }

        $records = [];
        $processedEmails = [];
        $chunkSize = 100;

        DB::connection('pgsql_master')->beginTransaction();
        try {
            while (($csvRow = fgetcsv($file)) !== false) {
                if (empty(array_filter($csvRow))) continue;
                if (count($csvRow) < count($csvHeader)) $csvRow = array_pad($csvRow, count($csvHeader), '');

                $csvAssociativeRow = array_combine($csvHeader, $csvRow);
                $email = trim($csvAssociativeRow['email'] ?? '');

                if (!empty($email) && in_array($email, $processedEmails)) {
                    $this->command->warn("Skipping duplicate email in CSV: {$email}");
                    continue;
                }
                
                $dataToInsert = [];
                foreach ($csvHeader as $headerName) {
                    if ($headerName === 'id') {
                        continue; // Skip the ID column to allow auto-increment
                    }
                    if (in_array($headerName, $dbColumns)) {
                        $dataToInsert[$headerName] = $this->transformValue($csvAssociativeRow[$headerName], $headerName);
                    }
                }

                if (!empty($email)) $processedEmails[] = $email;
                $records[] = $dataToInsert;

                if (count($records) >= $chunkSize) {
                    DB::connection('pgsql_master')->table('m_karyawan')->insert($records);
                    $records = [];
                }
            }

            if (!empty($records)) {
                DB::connection('pgsql_master')->table('m_karyawan')->insert($records);
            }

            DB::connection('pgsql_master')->commit();
            $this->command->info('Karyawan data seeded successfully!');

        } catch (\Exception $e) {
            DB::connection('pgsql_master')->rollBack();
            $this->command->error('Error seeding Karyawan data: ' . $e->getMessage());
            Log::error('Seeder Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
        } finally {
            fclose($file);
        }

        // --- NEW SECTION: Create Users ---
        $this->command->info('Creating or updating users from karyawan data...');
        $karyawansWithEmail = Karyawan::whereNotNull('email')->where('email', '!=', '')->get();

        foreach ($karyawansWithEmail as $karyawan) {
            try {
                $user = User::updateOrCreate(
                    ['email' => $karyawan->email],
                    [
                        'karyawan_id' => $karyawan->id,
                        'password' => Hash::make(Str::random(10)), // Default password
                        'is_active' => $karyawan->aktif,
                    ]
                );

                if ($user->wasRecentlyCreated) {
                    $this->command->line("  -> User CREATED for: {$karyawan->nama_karyawan} ({$karyawan->email})");
                } else {
                    $this->command->line("  -> User UPDATED for: {$karyawan->nama_karyawan} ({$karyawan->email})");
                }

            } catch (\Exception $e) {
                $this->command->error("  -> Failed to create user for {$karyawan->email}: " . $e->getMessage());
                Log::error("KaryawanSeeder: Failed user creation for email {$karyawan->email}. Error: " . $e->getMessage());
            }
        }
        $this->command->info('User creation process finished.');
    }

    protected function transformValue($value, $columnName)
    {
        $trimmedValue = trim($value);
        if (empty($trimmedValue) || $trimmedValue === '0/0/0' || strtolower($trimmedValue) === '#n/a') {
            return null;
        }

        $dateColumns = [
            'tgl_lahir', 'tgl_masuk', 'tgl_keluar', 'tgl_finish_contract', 'id_customer_expiredate', 
            'no_ktp_expiredate', 'no_sim_a_expiredate', 'no_sim_b1_expiredate', 
            'no_sim_b2_umum_expiredate', 'no_sim_c_expiredate', 'married_date', 
            'npwp_effectivedate', 'agreement_expire',
        ];

        $dateTimeColumns = ['reactive_date', 'date_created', 'last_updated'];

        if (in_array($columnName, $dateColumns) || in_array($columnName, $dateTimeColumns)) {
            try {
                // Handle 'd/m/Y' format first
                return Carbon::createFromFormat('d/m/Y', $trimmedValue)->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                try {
                    // Fallback for other formats like 'Y-m-d H:i:s' or 'Y-m-d'
                    return Carbon::parse($trimmedValue)->format('Y-m-d H:i:s');
                } catch (\Exception $e2) {
                    Log::warning("Could not parse date/datetime '{$trimmedValue}' for column '{$columnName}'. " . $e2->getMessage());
                    return null;
                }
            }
        }
        
        if ($columnName === 'aktif') {
            return strtoupper($trimmedValue) === 'Y';
        }

        return $trimmedValue;
    }
}