<?php

namespace App\Services;
use App\Models\DocumentPage;
use App\Models\Document;
use Illuminate\Http\UploadedFile;

class DocumentPageService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        // Initialize any dependencies here if needed
    }

    public function store(Document $document, UploadedFile $file)
    {
        // Store the uploaded file, save metadata in DocumentPage, and associate it with the document
    }

    public function show(DocumentPage $page)
    {
        // Return a response to download the document page file
    }

    public function edit(DocumentPage $page, string $newName)
    {
        // Update the original_name of the document page
    }

    public function destroy(DocumentPage $page)
    {
        // Delete the document page record and optionally remove the file from storage
    }
}
