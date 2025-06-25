<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;

use App\Models\Tag;
use App\Models\DocumentType;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $documents = Document::with(['tags', 'type', 'creator'])->latest()->get();
        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $documentTypes = DocumentType::all();
        return view('documents.create', compact('documentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request)
    {
        DB::beginTransaction();

        try {
            $document = Document::create([
                'name' => $request->name,
                'summary' => $request->summary,
                'document_type_id' => $request->document_type_id,
                'created_by' => 1, 
            ]);

            
            $tagIds = [];
            foreach ($request->tags as $tagName) {
                $trimmed = trim($tagName);
                if ($trimmed !== '') {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
            }
            $document->tags()->sync($tagIds);

            if ($request->hasFile('pages')) {
                foreach ($request->file('pages') as $file) {
                    $path = $file->store("{$document->type->name}/{$document->id}", 'public');

                    $originalName = $file->getClientOriginalName();
                    $document->pages()->create([
                        'file_path' => $path,
                        'document_name' => $originalName,
                    ]);
                }
            } 

            DB::commit();
            return redirect()->route('documents.index')->with('success', 'Document uploaded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document) 
    {
        $document->load(['type', 'tags', 'creator', 'pages']); // eager load relationships
        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document) 
    {
        $documentTypes = DocumentType::all();
        $tags = $document->tags->pluck('name')->toArray();

        return view('documents.edit', compact('document', 'documentTypes', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document) 
    {
        DB::beginTransaction();

        try {
            $document->update([
                'name' => $request->name,
                'summary' => $request->summary,
                'document_type_id' => $request->document_type_id,
            ]);

            $tagIds = [];
            foreach ($request->tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $tagIds[] = $tag->id;
            }
            $document->tags()->sync($tagIds);

            DB::commit();
            return redirect()->route('documents.index')->with('success', 'Document updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document) {
        $document->delete();
        return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }

    public function trash()
    {
        $documents = Document::onlyTrashed()->with(['type', 'creator', 'tags'])->latest()->get();
        return view('documents.trash', compact('documents'));
    }

    public function restore($id) 
    {
        $document = Document::withTrashed()->findOrFail($id);
        $document->restore();

        return redirect()->route('documents.index')->with('success', 'Document restored successfully.');

    }
    

    public function forceDelete($id)
    {
        $document = Document::withTrashed()->findOrFail($id);

        $path = "{$document->type->name}/{$document->id}";
        Storage::disk("public")->deleteDirectory($path);

        $document->forceDelete();

        return redirect()->route('documents.index')->with('success', 'Document permanently deleted.');
    }
}
