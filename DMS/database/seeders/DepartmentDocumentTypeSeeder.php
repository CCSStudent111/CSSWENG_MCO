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

        $hospitalTypeIds = DocumentType::where('is_hospital', true)->pluck('id');
        $generalTypes = DocumentType::where('is_hospital', false)->get();

        foreach ($departments as $department) {
            $randomGeneralTypeIds = $generalTypes->random(rand(1, min(3, $generalTypes->count())))->pluck('id');

            // Merge both hospital and general types
            $allTypeIds = $hospitalTypeIds->merge($randomGeneralTypeIds)->unique();

            // Attach to department
            $department->documentTypes()->sync($allTypeIds);
        }
    }
}
