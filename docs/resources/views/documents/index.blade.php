@extends('layouts.app')

@section('content')
<div class="container mt-5">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h2>All Documents</h2>

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
                    <td>{{ $document->type->name }}</td>
                    <td>
                        @foreach ($document->tags as $tag)
                            <span class="badge bg-secondary">{{ $tag->name }}</span>
                        @endforeach
                    </td>
                    <td>{{ $document->creator->name }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('documents.show', $document->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                            
                            <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>

                            <form action="{{ route('documents.destroy', $document->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No documents found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('documents.create') }}" class="btn btn-success">Create Document</a>
    <a href="{{ route('documents.trash') }}" class="btn btn-warning ms-2">View Trash</a>
</div>
@endsection