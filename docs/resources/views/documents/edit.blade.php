@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Edit Document</h2>

    <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Document Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $document->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="summary" class="form-label">Summary</label>
            <input type="text" name="summary" id="summary" class="form-control" value="{{ old('summary', $document->summary) }}" required>
        </div>

        <div class="mb-3">
            <label for="document_type_id" class="form-label">Document Type</label>
            <select name="document_type_id" id="document_type_id" class="form-select" required>
                @foreach ($documentTypes as $type)
                    <option value="{{ $type->id }}" {{ $type->id == $document->document_type_id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tags</label>
            @foreach ($tags as $index => $tag)
                <input type="text" name="tags[]" class="form-control mb-1" value="{{ $tag }}">
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Update Document</button>
        <a href="{{ route('documents.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection
