<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Services\DocumentService;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Services\TrashService;
use Inertia\Inertia;

class DocumentController extends Controller
{
    public function __construct(protected DocumentService $documentService, protected TrashService $trashService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $user = Auth::user()->load('department.documentTypes'); // uncomment when login implemented
        $user = User::with('department.documentTypes')->find(1);
        $documentTypesIds = $user->department->documentTypes()->where('is_hospital', false)->pluck('id');

        $documents = Document::with(['type', 'tags', 'creator'])->whereIn('document_type_id', $documentTypesIds)
            ->latest()->get();


        return Inertia::render('Documents/Index', [
            'documents'=> $documents,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $user = Auth::user()->load('department.documentTypes');  // uncomment when login implemented
        $user = User::with('department.documentTypes')->find(1);
        $documentTypes = $user->department->documentTypes->where('is_hospital', false)->values();

        return Inertia::render('Documents/Create', [
            'documentTypes'=> $documentTypes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request)
    {
        $validated = $request->validated();
        $validated['created_by'] = 1;

        $document = $this->documentService->create($validated);
        return redirect()->route('documents.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        $document->delete();
        return redirect()->route('documents.index');
    }


    public function trash()
    {
        $documents = $this->trashService->getTrashed(Document::class);

        return Inertia::render('Documents/Trash', [
            'documents'=> $documents,
        ]);
    }

    public function restore(Document $document)
    {
        $this->trashService->restore(Document::class, $document->id);

        return redirect()->route('documents.trash');
    }

    public function forceDelete(Document $document)
    {
        $folderPath = "{$document->type->name}/{$document->id}";
        Storage::disk('public')->deleteDirectory($folderPath);

        $this->trashService->forceDelete(Document::class, $document->id);
        return redirect()->route('documents.trash');
    }
}
