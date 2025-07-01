<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DocumentType;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Report',
            'Invoice',
            '201 files',
            'BIR Reports',
        ];

        foreach ($types as $type) {
            DocumentType::create(['name' => $type]);
        }

        $hospitalTypes = [
            'Project Documentation',
            'Service Level Aggreement'
        ];

        foreach ($hospitalTypes as $type) {
            DocumentType::create([
                'name' => $type,
                'is_hospital' => true,
            ]);
        }
    }
}
