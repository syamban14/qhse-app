<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class MUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::connection('pgsql_master')->disableQueryLog();
        $this->command->info('Seeding m_unit table...');

        // Define files and their categories
        $filesToSeed = [
            [
                'path' => 'dbs/master/csv/Unit Bulk Project 1 2025.csv',
                'category' => 'bulk',
                'skip' => 4,
            ],
            [
                'path' => 'dbs/master/csv/Unit DT Project 2 2025.csv',
                'category' => 'dumptruck',
                'skip' => 4,
            ],
            [
                'path' => 'dbs/master/csv/Unit Trans Cilegon Project 3.csv',
                'category' => 'transport',
                'skip' => 4,
            ],
        ];

        foreach ($filesToSeed as $file) {
            $this->seedFromFile(
                $file['path'],
                $file['category'],
                $file['skip']
            );
        }

        $this->command->info('m_unit table seeded successfully from all sources.');
    }

    /**
     * Seed data from a specific CSV file.
     *
     * @param string $filePath
     * @param string $category
     * @param int $skipRows
     * @return void
     */
    private function seedFromFile(string $filePath, string $category, int $skipRows): void
    {
        $csvPath = base_path($filePath);

        if (!file_exists($csvPath)) {
            $this->command->warn("File not found: {$csvPath}");
            return;
        }

        $this->command->info("Processing {$filePath} with category '{$category}'...");

        LazyCollection::make(function () use ($csvPath) {
            $handle = fopen($csvPath, 'r');
            while (($line = fgetcsv($handle)) !== false) {
                yield $line;
            }
            fclose($handle);
        })
        ->skip($skipRows) // Skip header rows
        ->map(function ($row) use ($category) {
            // Check if the essential columns are not empty and it's a valid data row
            if (!empty($row[1]) && !empty($row[2]) && is_numeric($row[0])) {
                return [
                    'no_unit' => $row[1],
                    'jenis_unit' => $row[2],
                    'kategori' => $category,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            return null;
        })
        ->filter() // Filter out null values
        ->chunk(100)
        ->each(function (LazyCollection $chunk) {
            DB::connection('pgsql_master')->table('m_unit')->upsert(
                $chunk->all(),
                ['no_unit'], // Unique identifier
                ['jenis_unit', 'kategori', 'updated_at'] // Columns to update if 'no_unit' exists
            );
        });
    }
}