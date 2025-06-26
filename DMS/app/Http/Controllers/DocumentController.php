<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use Illuminate\Http\Request;
use PhpParser\Comment\Doc;
use App\Services\DocumentService;
use App\Services\TrashService;
class DocumentController extends Controller
{
    public function __construct(protected DocumentService $documentService, protected TrashService $trashService)
    {  

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        //
    }

    public function trash()
    {
        // Display a list of soft-deleted documents (trash).
    }

    
    public function restore(int $id)
    {
        // Restore a soft-deleted document
    }

    
    public function forceDelete(int $id)
    {
        // Permanently delete a soft-deleted document
    }
}
