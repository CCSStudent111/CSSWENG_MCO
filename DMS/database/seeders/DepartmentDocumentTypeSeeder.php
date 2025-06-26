<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\DocumentType;

class DepartmentDocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all();
        $documentTypes = DocumentType::all();

        foreach ($departments as $department) {
            $randomDocumentTypes = $documentTypes->random(rand(1, 3))->pluck('id');
            $department->documentTypes()->attach($randomDocumentTypes);
        }
    }
}
