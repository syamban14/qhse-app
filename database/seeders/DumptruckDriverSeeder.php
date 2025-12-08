<?php

namespace Database\Seeders;

use App\Models\Master\Driver;
use App\Models\Master\Karyawan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DumptruckDriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding Dumptruck drivers from CSV...');

        $csvPath = base_path('dbs/master/csv/Monitoring Driver DT 2025.csv');
        if (!file_exists($csvPath)) {
            $this->command->error('CSV file not found at ' . $csvPath);
            return;
        }

        $file = fopen($csvPath, 'r');
        if ($file === false) {
            $this->command->error('Could not open CSV file.');
            return;
        }

        // Skip header rows (adjust number as needed)
        for ($i = 0; $i < 4; $i++) {
            fgetcsv($file);
        }

        DB::connection('pgsql_master')->beginTransaction();

        try {
            while (($row = fgetcsv($file)) !== false) {
                if (empty($row[1])) { // Skip empty payroll_id
                    continue;
                }

                $payrollId = trim($row[1]);

                $karyawan = Karyawan::where('payroll_id', $payrollId)->first();

                if ($karyawan) {
                    Driver::updateOrCreate(
                        ['karyawan_id' => $karyawan->id],
                        ['driver_category' => 'DUMPTRUCK']
                    );
                    $this->command->line("Processed driver with Payroll ID: $payrollId");
                } else {
                    $this->command->warn("Karyawan not found for Payroll ID: $payrollId");
                }
            }

            DB::connection('pgsql_master')->commit();
            $this->command->info('Dumptruck driver seeding completed successfully.');

        } catch (\Exception $e) {
            DB::connection('pgsql_master')->rollBack();
            $this->command->error('An error occurred during seeding: ' . $e->getMessage());
        } finally {
            fclose($file);
        }
    }
}