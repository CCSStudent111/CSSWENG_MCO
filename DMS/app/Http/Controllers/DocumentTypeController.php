<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use App\Http\Requests\StoreDocumentTypeRequest;
use App\Http\Requests\UpdateDocumentTypeRequest;
use App\Services\TrashService;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected TrashService $trashService)
    {  

    }
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
    public function store(StoreDocumentTypeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentType $documentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentType $documentType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentTypeRequest $request, DocumentType $documentType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentType $documentType)
    {
        //
    }

    public function trash()
    {
        // Display a list of soft-deleted document types (trash).
    }

    
    public function restore(int $id)
    {
        // Restore a soft-deleted document type
    }

    
    public function forceDelete(int $id)
    {
        // Permanently delete a soft-deleted document type
    }
}
