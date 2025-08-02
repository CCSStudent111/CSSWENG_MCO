<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(DepartmentSeeder::class);
        $this->call(DocumentTypeSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(DepartmentDocumentTypeSeeder::class);
        $this->call(DocumentSeeder::class);
    }
}
