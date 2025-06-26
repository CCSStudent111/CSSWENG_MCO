<?php

namespace App\Http\Controllers;

use App\Models\DocumentPage;
use App\Http\Requests\StoreDocumentPageRequest;
use App\Http\Requests\UpdateDocumentPageRequest;
use App\Services\DocumentPageService;

class DocumentPageController extends Controller
{
    public function __construct(protected DocumentPageService $service) {} 
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
    public function store(StoreDocumentPageRequest $request)
    {
        // upload a new page
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentPage $documentPage)
    {
        // download a page (optional)
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentPage $documentPage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentPageRequest $request, DocumentPage $documentPage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentPage $documentPage)
    {
        // delete a page
    }
}
