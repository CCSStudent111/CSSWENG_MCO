<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\Document;
use App\Models\User;
use App\Services\DocumentService;
use App\Services\TrashService;
use Inertia\Inertia;

class DocumentController extends Controller
{
    public function __construct(protected DocumentService $documentService, protected TrashService $trashService) {}
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
    public function create()
    {
        $user = User::with('department.documentTypes')->find(1);
        $hospitals = Hospital::all();
        $documentTypes = $user->department->documentTypes()->where('is_hospital', true)->get()->values();

        return Inertia::render('Hospitals/Documents/Create', [
            'hospitals' => $hospitals,
            'documentTypes' => $documentTypes   
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request, Hospital $hospital)
    {
        $validated = $request->validated();
        $validated['created_by'] = 1;

        $hospitalId = $validated['hospital_id'];
        unset($validated['hospital_id']);

        $document = $this->documentService->create($validated);

        Hospital::findOrFail($hospitalId)->documents()->attach($document->id);

        return redirect()->route('hospitals.index');
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
