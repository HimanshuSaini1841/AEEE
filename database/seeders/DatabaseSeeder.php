<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AeeeMockTest1Seeder::class,
            Aeee2022PyqMockTestSeeder::class,
            AdminUserSeeder::class,
            DynamicFieldsSeeder::class,
            AppSettingsSeeder::class,
        ]);
    }
}
