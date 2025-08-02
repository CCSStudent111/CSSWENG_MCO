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
            'Contracts',
            'Proposals',
            'Memos',
            'User Manuals',
            'Meeting Minutes',
            'Policies',
            'Guidelines',
            'Technical Specifications',
            'Research Papers',
            'Presentations',
            'BIR Reports',
        ];

        foreach ($types as $type) {
            DocumentType::create(['name' => $type]);
        }
    }
}
