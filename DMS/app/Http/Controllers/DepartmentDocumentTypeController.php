<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\DocumentType;
use Inertia\Inertia;

class DepartmentDocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Only administrators can access this page.');
        }

        $departments = Department::with('documentTypes')->get();
        $documentTypes = DocumentType::all();

        return Inertia::render('DepartmentDocumentTypes/Index', [
            'departments' => $departments,
            'documentTypes' => $documentTypes,
        ]);
    }

    /**
     * Attach a document type to a department
     */
    public function attach(Department $department, DocumentType $type)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Only administrators can modify department document types.');
        }

        if (!$department->documentTypes()->where('document_type_id', $type->id)->exists()) {
            $department->documentTypes()->attach($type->id);
        }

        // Just redirect back with fresh data
        return redirect()->back();
    }

    /**
     * Detach a document type from a department
     */
    public function detach(Department $department, DocumentType $type)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Only administrators can modify department document types.');
        }

        $department->documentTypes()->detach($type->id);
        
        // Just redirect back with fresh data
        return redirect()->back();
    }

    /**
     * Bulk attach multiple document types to a department
     */
    public function bulkAttach(Request $request, Department $department)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Only administrators can modify department document types.');
        }

        $request->validate([
            'document_type_ids' => 'required|array',
            'document_type_ids.*' => 'exists:document_types,id'
        ]);

        $department->documentTypes()->syncWithoutDetaching($request->document_type_ids);
        
        return redirect()->back();
    }

    /**
     * Bulk detach multiple document types from a department
     */
    public function bulkDetach(Request $request, Department $department)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Only administrators can modify department document types.');
        }

        $request->validate([
            'document_type_ids' => 'required|array',
            'document_type_ids.*' => 'exists:document_types,id'
        ]);

        $department->documentTypes()->detach($request->document_type_ids);
        
        return redirect()->back();
    }
}
