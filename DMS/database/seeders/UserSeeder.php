<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->manager()->create();
        User::factory()->create();

        // Create admin user with defined credentials
        User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'first_name' => 'Admin',
            'middle_name' => '',
            'last_name' => 'User',
            'suffix' => '',
            'date_of_birth' => '1990-01-01',
            'is_admin' => true,
            'department_id' => 1, 
        ]);
    }
}
