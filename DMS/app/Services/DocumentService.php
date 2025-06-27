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

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $document = Document::create([
                'name' => $data['name'],
                'summary' => $data['summary'],
                'document_type_id' => $data['document_type_id'],
                'created_by' => $data['created_by'] ?? 1,
            ]);

            // Tags
            $tagIds = [];
            foreach ($data['tags'] ?? [] as $tagName) {
                $trimmed = trim($tagName);
                if ($trimmed !== '') {
                    $tag = Tag::firstOrCreate(['name' => $trimmed]);
                    $tagIds[] = $tag->id;
                }
            }
            $document->tags()->sync($tagIds);

            // Files
            if (!empty($data['pages'])) {
                foreach ($data['pages'] as $file) {
                    $path = $file->store("{$document->type->name}/{$document->id}", 'public');

                    $document->pages()->create([
                        'file_path' => $path,
                        'document_name' => $file->getClientOriginalName(),
                    ]);
                }
            }

            DB::commit();
            return $document;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Document $document, array $data)
    {
        // Update and return the document
    }

    public function destroy(Document $document)
    {
        // Soft delete the document
    }


}
