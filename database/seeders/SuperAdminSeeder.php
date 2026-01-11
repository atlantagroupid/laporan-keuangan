<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@laporan.test',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }
}
