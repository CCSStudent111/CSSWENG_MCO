@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Upload Document</h2>
    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        
        <div class="mb-3">
            <label for="name" class="form-label">Document Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Document name" value="{{ old('name') }}">
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        
        <div class="mb-3">
            <label for="summary" class="form-label">Summary</label>
            <input type="text" name="summary" id="summary" class="form-control" placeholder="Summary" value="{{ old('summary') }}">
            @error('summary')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        
        <div class="mb-3">
            <label for="document_type_id" class="form-label">Document Type</label>
            <select name="document_type_id" id="document_type_id" class="form-select">
                <option value="">-- Select Document Type --</option>
                @foreach($documentTypes as $type)
                    <option value="{{ $type->id }}" {{ old('document_type_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            @error('document_type_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Tags</label>
            @php $oldTags = old('tags', ['', '', '']); @endphp
            @foreach ($oldTags as $index => $tag)
                <input type="text" name="tags[]" class="form-control mb-1" placeholder="Tag {{ $index + 1 }}" value="{{ $tag }}">
            @endforeach
            @error('tags.*')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        
        <div class="mb-3">
            <label for="pages" class="form-label">Document Pages</label>
            <input type="file" name="pages[]" id="pages" class="form-control" multiple>
            @error('pages')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Upload</button>
        <a href="{{ route('documents.index') }}" class="btn btn-secondary ms-2">Back to Documents</a>
    </form>
</div>
@endsection
