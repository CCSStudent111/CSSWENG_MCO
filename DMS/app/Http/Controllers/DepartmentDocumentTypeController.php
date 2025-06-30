<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\DocumentType;

class DepartmentDocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // display attach detach view (settings)
    }

    public function attach(Department $department, DocumentType $type)
    {
        if(!$department->documentTypes()->where('document_type_id', $type->id)->exists()) 
        {
            $department->documentTypes()->attach($type->id);
        }
    }

    public function detach(Department $department, DocumentType $type)
    {
        $department->documentTypes()->detach($type->id);
    }
}
