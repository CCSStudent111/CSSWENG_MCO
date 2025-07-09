<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documentTypes = DocumentType::withTrashed()->get();

        return Inertia::render('DocumentTypes/Index', [
            'documentTypes' => $documentTypes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('DocumentTypes/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:document_types,name',
        ]);

        DocumentType::create($validated);

        return redirect()->route('documentTypes.index')->with('success', 'Document type created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentType $documentType)
    {
        return Inertia::render('DocumentTypes/Edit', [
            'documentType' => $documentType
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentType $documentType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:document_types,name,' . $documentType->id,
        ]);

        $documentType->update($validated);

        return redirect()->route('documentTypes.index')->with('success', 'Document type updated successfully.');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();

        return redirect()->route('documentTypes.index')->with('success', 'Document type deleted successfully.');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $documentType = DocumentType::withTrashed()->find($id);

        if ($documentType) {
            $documentType->restore();
            return redirect()->route('documentTypes.index')->with('success', 'Document type restored successfully.');
        }

        return redirect()->route('documentTypes.index')->with('error', 'Document type not found.');
    }

    /**
     * Permanently delete the specified resource from storage.
     */
    public function forceDelete($id)
    {
        $documentType = DocumentType::withTrashed()->findOrFail($id);
        $documentType->forceDelete();

        return redirect()->route('documentTypes.index')->with('success', 'Document type permanently deleted.');
    }
}
