<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateUnitCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::connection('pgsql_master')
            ->table('m_unit')
            ->update(['kategori' => 'bulk']);

        $this->command->info('Successfully updated the kategori for all existing units to "bulk".');
    }
}