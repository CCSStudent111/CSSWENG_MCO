<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Services\DocumentService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\Tag;
use App\Models\User;
use App\Models\Hospital;
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
            'documents' => $documents,
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
            'documentTypes' => $documentTypes
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
        $document->load(['type', 'tags', 'creator', 'pages']);

        return Inertia::render('Documents/Show', [
            'document' => $document
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        $user = User::with('department.documentTypes')->find(1);
        $documentTypes = $user->department->documentTypes()->where('is_hospital', false)->get()->values();

        $document->load(['type', 'tags', 'creator', 'pages']);

        return Inertia::render('Documents/Edit', [
            'document' => $document,
            'documentTypes' => $documentTypes
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        $this->documentService->update($document, $request->validated());

        return redirect()->route('documents.show', $document->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        $document->delete();
        return redirect()->route('documents.index');
    }

    public function logs()
    {
        $logs = Activity::where('subject_type', Document::class)
            ->with([
                'causer',
                'subject' => function ($query) {
                    $query->withTrashed(); 
                }
            ])
            ->latest()
            ->get()
            ->map(function ($activity) {
                $subject = $activity->subject;

                return [
                    'description' => $activity->description,
                    'changes' => $activity->properties->toArray(),
                    'causer' => $activity->causer,
                    'document' => [
                        'id' => $subject->id ?? null,
                        'name' => $subject->name
                            ?? $activity->properties['old']['name']
                            ?? $activity->properties['attributes']['name']
                            ?? 'N/A',
                    ],
                    'date' => $activity->created_at,
                ];
            });

        return Inertia::render('Documents/AllLogs', [
            'logs' => $logs,
        ]);
    }

    public function documentLogs(Document $document)
    {
        $document->load(['activities' => function ($query) {
            $query->latest();
        }, 'activities.causer']);
        return Inertia::render('Documents/Logs', [
            'document' => $document,
            'logs' => $document->activities->map(function ($activity) {
                return [
                    'description' => $activity->description,
                    'changes' => $activity->properties->toArray(),
                    'causer' => $activity->causer,
                    'date' => $activity->created_at,
                ];
            }),
        ]);
    }


    public function trash()
    {
        $documents = $this->trashService
            ->getTrashed(Document::class, ['type', 'tags', 'creator', 'pages'])
            ->whereHas('type', function ($query) {
                $query->where('is_hospital', false);
            })
            ->get();

        return Inertia::render('Documents/Trash', [
            'documents' => $documents,
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

    public function search(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('type');

        $results = [];

        switch ($type) {
            case 'document_name':
                $results = Document::with(['tags', 'hospitals', 'creator', 'type'])
                    ->where('name', 'REGEXP', $query)
                    ->get();
                break;

            case 'tag':
                $tagPatterns = is_array($query) ? $query : explode(',', $query);
                $tagPatterns = array_map('trim', $tagPatterns);
                $matchType = $request->input('match_type', 'all');

                $results = Document::with(['tags', 'hospitals', 'creator', 'type']);

                if (empty($tagPatterns[0])) {
                    $results = $results->doesntHave('tags');
                } else {
                    $results = $results->whereHas('tags', function ($q) use ($tagPatterns, $matchType) {
                        $q->where(function ($subQuery) use ($tagPatterns, $matchType) {
                            foreach ($tagPatterns as $pattern) {
                                if ($matchType === 'any') {
                                    $subQuery->orWhere('name', 'REGEXP', $pattern);
                                } else {
                                    $subQuery->where('name', 'REGEXP', $pattern);
                                }
                            }
                        });
                    });
                }

                $results = $results->get();
                break;


            case 'document_type':
                $results = Document::with(['tags', 'hospitals', 'creator', 'type'])
                    ->whereHas('type', function ($q) use ($query) {
                        $q->where('name', 'REGEXP', $query);
                    })
                    ->get();
                break;

            case 'hospital':
                $results = Document::with(['tags', 'hospitals', 'creator', 'type'])
                    ->whereHas('hospitals', function ($q) use ($query) {
                        $q->where('name', 'REGEXP', $query);
                    })
                    ->get();
                break;

            case 'user':
                $results = User::whereRaw("CONCAT(first_name, ' ', last_name) REGEXP ?", [$query])
                    ->get();
                break;

            case 'tag+type':
                $tags = $request->input('tags', []);
                $docType = $request->input('document_type');
                $matchType = $request->input('match_type', 'all');

                $results = Document::with(['tags', 'hospitals', 'creator', 'type'])
                    ->whereHas('type', function ($q) use ($docType) {
                        $q->where('name', 'REGEXP', $docType);
                    });

                if (empty($tags) || (count($tags) === 1 && trim($tags[0]) === '')) {
                    $results = $results->doesntHave('tags');
                } else {
                    $results = $results->whereHas('tags', function ($q) use ($tags, $matchType) {
                        $q->where(function ($subQuery) use ($tags, $matchType) {
                            foreach ($tags as $pattern) {
                                if ($matchType === 'any') {
                                    $subQuery->orWhere('name', 'REGEXP', $pattern);
                                } else {
                                    $subQuery->where('name', 'REGEXP', $pattern);
                                }
                            }
                        });
                    });
                }

                $results = $results->get();
                break;
        }

        return response()->json($results);
    }

}
