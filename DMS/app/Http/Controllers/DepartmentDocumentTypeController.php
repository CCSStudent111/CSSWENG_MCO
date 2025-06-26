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
        // Attach a document type to a department
    }

    public function detach(Department $department, DocumentType $type)
    {
        // Detach a document type from a department
        
    }
}
