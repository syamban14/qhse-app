<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Master\Karyawan;
use App\Models\Master\Driver;
use Illuminate\Support\Facades\Log;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Starting DriverSeeder with robust ID fetching...');

        $monitoring_csv_path = 'dbs/master/csv/Monitoring Driver DT 2025.csv';
        if (!file_exists($monitoring_csv_path)) {
            $this->command->error("Driver monitoring CSV file not found at: {$monitoring_csv_path}");
            return;
        }

        // Use a transaction on the master database connection directly
        DB::connection('pgsql_master')->beginTransaction();
        $this->command->info("Transaction started on 'pgsql_master' connection.");

        try {
            $file = fopen($monitoring_csv_path, 'r');
            fgetcsv($file); // Skip title row
            $header = fgetcsv($file); // Main header
            fgetcsv($file); // Month row

            $payroll_id_idx = array_search('PAYROLL ID', $header);
            $nama_driver_idx = array_search('NAMA DRIVER', $header);

            if ($payroll_id_idx === false || $nama_driver_idx === false) {
                throw new \Exception('Required columns "PAYROLL ID" or "NAMA DRIVER" not found in CSV header.');
            }

            $rowCount = 0;
            while (($row = fgetcsv($file)) !== FALSE) {
                $rowCount++;
                if (count($row) <= max($payroll_id_idx, $nama_driver_idx) || empty($row[$payroll_id_idx])) {
                    continue;
                }

                $payroll_id = trim($row[$payroll_id_idx]);
                $nama_driver = trim($row[$nama_driver_idx]);

                // 1. Find or Create Karyawan
                $karyawan = Karyawan::firstOrCreate(
                    ['payroll_id' => $payroll_id],
                    [
                        'nama_karyawan' => $nama_driver,
                        'title'         => 'DRIVER DUMPTRUCK',
                        'aktif'         => true,
                        'status'        => 'MITRA KERJA',
                    ]
                );

                // 2. Explicitly fetch the ID using a direct query to bypass model hydration issues.
                $karyawan_id = DB::connection('pgsql_master')->table('m_karyawan')->where('payroll_id', $payroll_id)->value('id');

                if ($karyawan_id) {
                    $this->command->line("Processing {$nama_driver} | Karyawan ID: {$karyawan_id}");
                    $driver = Driver::updateOrCreate(
                        ['karyawan_id' => $karyawan_id],
                        [
                            'driver_category' => 'DUMPTRUCK',
                            'status'          => 'ACTIVE',
                        ]
                    );
                } else {
                    $this->command->error("CRITICAL: Could not find or create an ID for Karyawan with payroll_id {$payroll_id}.");
                    Log::error("DriverSeeder: Could not retrieve ID for Karyawan with Payroll ID {$payroll_id}.");
                }
            }

            fclose($file);
            DB::connection('pgsql_master')->commit();
            $this->command->info("Transaction committed successfully on 'pgsql_master'.");
            $this->command->info("DriverSeeder processed {$rowCount} rows.");

        } catch (\Exception $e) {
            DB::connection('pgsql_master')->rollBack();
            $this->command->error("An error occurred: " . $e->getMessage());
            Log::error("DriverSeeder failed: " . $e->getMessage());
            $this->command->error("Transaction rolled back.");
        }
    }
}