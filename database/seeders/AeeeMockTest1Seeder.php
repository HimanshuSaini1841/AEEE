<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AeeeMockTest1Seeder extends Seeder
{
    /**
     * Run the AEEE Mock Test 1 Seeder.
     */
    public function run(): void
    {
        $this->call(MockTestSeeder::class);
    }
}
