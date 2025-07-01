<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\Document;
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
    public function index(Hospital $hospital)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Hospital $hospital)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Hospital $hospital)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Hospital $hospital, Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hospital $hospital, Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hospital $hospital, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hospital $hospital, Document $document)
    {
        //
    }
}
