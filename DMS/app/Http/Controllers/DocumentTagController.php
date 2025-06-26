<?php

namespace App\Http\Controllers;
use App\Models\Document;
use App\Models\Tag;

use Illuminate\Http\Request;

class DocumentTagController extends Controller
{
    public function attach(Request $request, Document $document)
    {
       
    }

    /**
     * Detach a tag from the document.
     */
    public function detach(Request $request, Document $document)
    {
        
    }
}
