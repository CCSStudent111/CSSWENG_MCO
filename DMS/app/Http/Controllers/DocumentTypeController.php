<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use App\Models\Document;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

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
        $user = auth()->user()->load('department.documentTypes');
        $documentTypes = $user->department->documentTypes->where('is_hospital', false)->values();
        
        // Add "None" option to the beginning of the collection
        $documentTypesWithNone = collect([
            (object) ['id' => null, 'name' => 'None']
        ])->concat($documentTypes);

        return Inertia::render('DocumentTypes/Create', [
            'documentTypes' => $documentTypesWithNone,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:document_types,name', 
            ]
        ], [
            'name.required' => 'Document type name is required.',
            'name.unique' => 'A document type with this name already exists.',
            'name.max' => 'Document type name cannot exceed 255 characters.'
        ]);

        DocumentType::create($validated);

        return redirect()->route('documentTypes.index')
            ->with('message', 'Document type created successfully!');
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('document_types', 'name')->ignore($documentType->id), // Ignore current record
            ]
        ], [
            'name.required' => 'Document type name is required.',
            'name.unique' => 'A document type with this name already exists.',
            'name.max' => 'Document type name cannot exceed 255 characters.'
        ]);

        $documentType->update($validated);

        return redirect()->route('documentTypes.index')
            ->with('message', 'Document type updated successfully!');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(DocumentType $documentType)
    {
        try {
            DB::transaction(function () use ($documentType) {
                \Log::info('Soft deleting document type', [
                    'id' => $documentType->id,
                    'name' => $documentType->name
                ]);
                
                // Update all documents that reference this type to null
                $documentsUpdated = Document::where('document_type_id', $documentType->id)
                    ->update(['document_type_id' => null]);
                
                \Log::info('Documents updated to null', ['count' => $documentsUpdated]);
                
                // Soft delete the document type
                $documentType->delete();
                
                \Log::info('Document type soft deleted successfully');
            });

            return redirect()->route('documentTypes.index')
                ->with('message', 'Document type moved to trash. Associated documents have been set to "None".');
                
        } catch (\Exception $e) {
            \Log::error('Error soft deleting document type', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('documentTypes.index')
                ->with('error', 'Error deleting document type: ' . $e->getMessage());
        }
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $documentType = DocumentType::withTrashed()->findOrFail($id);
        $documentType->restore();

        return redirect()->route('documentTypes.index')
            ->with('message', 'Document type restored successfully!');
    }

    /**
     * Permanently delete the specified resource from storage.
     */
    public function forceDelete($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $documentType = DocumentType::withTrashed()->findOrFail($id);
                
                \Log::info('Force deleting document type', [
                    'id' => $documentType->id,
                    'name' => $documentType->name
                ]);
                
                // First, update all documents that reference this type to null
                $documentsUpdated = Document::where('document_type_id', $documentType->id)
                    ->update(['document_type_id' => null]);
                
                \Log::info('Documents updated to null', ['count' => $documentsUpdated]);
                
                // Also check for any soft-deleted documents
                $trashedDocumentsUpdated = Document::onlyTrashed()
                    ->where('document_type_id', $documentType->id)
                    ->update(['document_type_id' => null]);
                
                \Log::info('Trashed documents updated to null', ['count' => $trashedDocumentsUpdated]);
                
                // Now we can safely force delete the document type
                $documentType->forceDelete();
                
                \Log::info('Document type force deleted successfully', ['id' => $id]);
            });

            return redirect()->route('documentTypes.index')
                ->with('message', 'Document type permanently deleted. Associated documents have been set to "None".');
                
        } catch (\Exception $e) {
            \Log::error('Error force deleting document type', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('documentTypes.index')
                ->with('error', 'Error permanently deleting document type: ' . $e->getMessage());
        }
    }
}
