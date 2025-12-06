<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(MasterDataPermissionsSeeder::class);
        $this->call(KaryawanSeeder::class);
        $this->call(MUnitSeeder::class);
        $this->call(AuditTemplateSeeder::class);
        $this->call(DumptruckDriverSeeder::class);
        $this->call(SyncDriverUsersSeeder::class);


        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
