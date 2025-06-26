<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DocumentPageService;
use App\Models\Hospital;
use App\Models\Document;
use App\Models\DocumentPage;

class DocumentPageController extends Controller
{
    public function __construct(protected DocumentPageService $service) {} 
    /**
     * Display a listing of the resource.
     */
    public function index(Hospital $hospital, Document $document)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Hospital $hospital, Document $document)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Hospital $hospital, Document $document)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentPage $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentPage $page)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentPage $page)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentPage $page)
    {
        //
    }
}
