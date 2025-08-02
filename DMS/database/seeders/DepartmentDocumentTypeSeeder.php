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
        $documentTypeIds = DocumentType::pluck('id')->toArray();

        foreach ($departments as $department) {
            if (empty($documentTypeIds)) {
                continue; 
            }

            $randomTypeIds = collect($documentTypeIds)
                ->random(rand(1, min(3, count($documentTypeIds))))
                ->toArray();

            $department->documentTypes()->sync($randomTypeIds);
        }
    }
}
