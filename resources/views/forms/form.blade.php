{{-- documents/form.blade.php --}}

<div class="mb-3">
    <label for="table_name" class="form-label">Table Name</label>
    <input type="text" class="form-control" name="table_name" value="{{ old('table_name', $document->table_name ?? '') }}">
</div>

<div class="mb-3">
    <label for="ref_id" class="form-label">Reference ID</label>
        <select name="ref_id" class="form-control">
            @foreach($relations as $relation)
                <option value="{{ $relation->relations_dtl_id }}">{{ $relation->title }}</option>
            @endforeach
        </select>
</div>

<div class="mb-3">
    <label for="uploaded_file_desc" class="form-label">File Description</label>
    <input type="text" class="form-control" name="uploaded_file_desc" value="{{ old('uploaded_file_desc', $document->uploaded_file_desc ?? '') }}">
</div>
<div class="mb-3">
    <label for="url" class="form-label">URL</label>
    <input type="text" class="form-control" name="url" value="{{old('url', $document->url ?? '')}}">
</div>
<div class="mb-3">
    <label for="uploaded_file" class="form-label">Document File</label>
    
    @if(isset($document) && $document->uploaded_file)
        {{-- Show existing file --}}
        <div class="existing-file mb-2">
            <span class="badge bg-success">Current File:</span>
            <a href="{{ asset('storage/' . $document->uploaded_file) }}" 
               target="_blank" 
               class="text-decoration-none">
                {{ basename($document->uploaded_file) }}
            </a>
            
            {{-- Optional: Add remove file checkbox --}}
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" 
                       name="remove_file" id="remove_file">
                <label class="form-check-label text-danger" for="remove_file">
                    Remove current file
                </label>
            </div>
        </div>
    @endif

    {{-- File input --}}
    <input type="file" name="uploaded_file" 
           class="form-control"
           @if(!isset($document)) required @endif>
    
    <small class="form-text text-muted">
        @if(isset($document) && $document->uploaded_file)
            Leave empty to keep existing file
        @else
            Please upload a file
        @endif
    </small>
</div>

<div class="mb-3">
    <label for="publication" class="form-label">Publication</label>
    <input type="text" class="form-control" name="publication" value="{{ old('publication', $document->publication ?? '') }}">
</div>



<div class="mb-3">
    <label for="is_disabled" class="form-label">Is Disabled</label>
    <select name="is_disabled" class="form-select">
        <option value="0" {{ old('is_disabled', $document->is_disabled ?? '') == '0' ? 'selected' : '' }}>No</option>
        <option value="1" {{ old('is_disabled', $document->is_disabled ?? '') == '1' ? 'selected' : '' }}>Yes</option>
    </select>
</div>


<div class="mb-3">
    <input type="submit" class="btn btn-primary" value="Save Document">
</div>
