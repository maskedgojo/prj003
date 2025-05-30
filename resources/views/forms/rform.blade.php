<div class="mb-3">
    <label for="relations_master_id">Relations Master ID</label>
    <input type="number" name="relations_master_id" class="form-control" 
           value="{{ old('relations_master_id', $relation->relations_master_id ?? '') }}">
</div>

<div class="mb-3">
    <label for="title">Title</label>
    <input type="text" name="title" class="form-control" 
           value="{{ old('title', $relation->title ?? '') }}">
</div>

<div class="mb-3">
    <label for="page_url">Page URL</label>
    <input type="text" name="page_url" class="form-control" 
           value="{{ old('page_url', $relation->page_url ?? '') }}">
</div>

<div class="mb-3">
    <label for="description">Description</label>
    <textarea name="description" class="form-control">{{ old('description', $relation->description ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label for="is_file_uploaded">Is File Uploaded</label>
    <select name="is_file_uploaded" class="form-select">
        <option value="Y" {{ old('is_file_uploaded', $relation->is_file_uploaded ?? 'N') == 'Y' ? 'selected' : '' }}>Yes</option>
        <option value="N" {{ old('is_file_uploaded', $relation->is_file_uploaded ?? 'N') == 'N' ? 'selected' : '' }}>No</option>
    </select>
</div>


<div class="mb-3">
    <label for="is_disabled">Is Disabled</label>
    <select name="is_disabled" class="form-select">
        <option value="0" {{ old('is_disabled', $relation->is_disabled ?? '0') == '0' ? 'selected' : '' }}>No</option>
        <option value="1" {{ old('is_disabled', $relation->is_disabled ?? '0') == '1' ? 'selected' : '' }}>Yes</option>
    </select>
</div>

<button type="submit" class="btn btn-primary">Save Relation</button>
