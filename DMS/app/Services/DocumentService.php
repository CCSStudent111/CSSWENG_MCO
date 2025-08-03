<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Tag;
use App\Models\User;
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

        $type = sha1($document->type->name);
        $id = sha1($document->id);

        foreach ($files as $file) {

            $fileName = $file->hashName();

            $path = $file->storeAs("{$type}/{$id}", $fileName, 'public');

            $document->pages()->create([
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
            ]);
        }
    }


    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::find($data['created_by']);

            if ($user && $user->isManager()) {
                $data['status'] = 'approved';
                $data['approved_by'] = $user->id;
            } 

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

            if (!empty($data['pages'])) {
                $this->storeFiles($document, $data['pages']);
            }

            return $document;
        });
    }
}
