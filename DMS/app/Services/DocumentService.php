<?php

namespace App\Services;
use App\Models\Document;
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
        // Create a new document using the given data
    }

    public function update(Document $document, array $data)
    {
        // Update and return the document
    }

    public function destroy(Document $document)
    {
        // Soft delete the document
    }

    public function attachTag(Document $document, int $tagId)
    {
        // Attach the tag to the document
    }

    public function detachTag(Document $document, int $tagId)
    {
        // Detach the tag from the document
    }
}
