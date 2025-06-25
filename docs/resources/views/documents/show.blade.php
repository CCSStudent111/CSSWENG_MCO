@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Document Details</h2>

    <div class="mb-3">
        <strong>Name:</strong> {{ $document->name }}
    </div>

    <div class="mb-3">
        <strong>Summary:</strong> {{ $document->summary }}
    </div>

    <div class="mb-3">
        <strong>Type:</strong> {{ $document->type->name ?? 'N/A' }}
    </div>

    <div class="mb-3">
        <strong>Tags:</strong>
        @forelse ($document->tags as $tag)
            <span class="badge bg-secondary">{{ $tag->name }}</span>
        @empty
            <span class="text-muted">No tags</span>
        @endforelse
    </div>

    <div class="mb-3">
        <strong>Created By:</strong> {{ $document->creator->name ?? 'N/A' }}
    </div>

    <div class="mb-3">
        <strong>Pages:</strong>
        <ul>
            @forelse ($document->pages as $page)
                <li>
                    <a href="{{ asset('storage/' . $page->file_path) }}" target="_blank">
                        {{ $page->document_name }}
                    </a>
                </li>
            @empty
                <li class="text-muted">No pages uploaded</li>
            @endforelse
        </ul>
    </div>

    <a href="{{ route('documents.index') }}" class="btn btn-secondary">← Back to Documents</a>
</div>
@endsection
