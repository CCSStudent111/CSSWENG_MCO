<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    private function attachTags(Document $document, array $tags)
    {
        $tagIds = collect($tags)
            ->map(fn($name) => trim($name))
            ->filter()
            ->map(fn($name) => Tag::firstOrCreate(["name" => $name])->id)
            ->all();

        $document->tags()->sync($tagIds);
    }

    private function storeFiles(Document $document, array $files)
    {
        foreach ($files as $file) {
            $path = $file->store("{$document->type->name}/{$document->id}", 'public');

            $document->pages()->create([
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
            ]);
        }
    }


    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $document = Document::create((array) $data);

            if (!empty($data['tags'])) {
                $this->attachTags($document, $data['tags']);
            }

            $this->storeFiles($document, $data['pages']);

            return $document;
        });
    }

    public function update(Document $document, array $data)
    {
        return DB::transaction(function () use ($document, $data) {
            $document->update((array) $data);

            $this->attachTags($document, $data['tags'] ?? []);
            $this->storeFiles($document, $data['pages']);
            
            return $document;
        });
    }
}
