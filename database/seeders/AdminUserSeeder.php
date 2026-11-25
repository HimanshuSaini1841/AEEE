<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        AdminUser::updateOrCreate(
            ['email' => 'admin@aeee.edu'],
            [
                'name' => 'AEEE Master Admin',
                'password' => Hash::make('Admin@123456'),
                'role' => 'superadmin',
                'is_active' => true,
            ]
        );
    }
}
