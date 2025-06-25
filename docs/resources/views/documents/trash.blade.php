@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Trashed Documents</h2>

    <table class="table table-bordered table-striped mt-4">
        <thead>
            <tr>
                <th>Name</th>
                <th>Summary</th>
                <th>Type</th>
                <th>Tags</th>
                <th>Created By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($documents as $document)
                <tr>
                    <td>{{ $document->name }}</td>
                    <td>{{ $document->summary }}</td>
                    <td>{{ $document->type->name ?? 'N/A' }}</td>
                    <td>
                        @foreach ($document->tags as $tag)
                            <span class="badge bg-secondary">{{ $tag->name }}</span>
                        @endforeach
                    </td>
                    <td>{{ $document->creator->name ?? 'N/A' }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <form action="{{ route('documents.restore', $document->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-success">Restore</button>
                            </form>

                            <form action="{{ route('documents.forceDelete', $document->id) }}" method="POST" onsubmit="return confirm('Permanently delete this document?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Force Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No trashed documents found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('documents.index') }}" class="btn btn-primary">← Back to Documents</a>
</div>
@endsection
