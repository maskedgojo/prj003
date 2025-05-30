@extends('layout')

@section('title', 'Relation Details')

@section('content')
    <h1>Relation Details (ID: {{ $relation->relations_dtl_id }})</h1>

    <div class="card p-3 mb-4">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>Relation ID</th>
                    <td>{{ $relation->relations_dtl_id }}</td>
                </tr>
                <tr>
                    <th>Relations Master ID</th>
                    <td>{{ $relation->relations_master_id }}</td>
                </tr>
                <tr>
                    <th>Title</th>
                    <td>{{ $relation->title }}</td>
                </tr>
                <tr>
                    <th>Page URL</th>
                    <td>{{ $relation->page_url }}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{{ $relation->description }}</td>
                </tr>
                <tr>
                    <th>Is File Uploaded</th>
                    <td>{{ $relation->is_file_uploaded }}</td>
                </tr>
                <tr>
                    <th>File Upload Count</th>
                    <td>{{ $relation->fileupload_count }}</td>
                </tr>
                <tr>
                    <th>Is Disabled</th>
                    <td>{{ $relation->is_disabled ? 'Yes' : 'No' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <h3>Documents related to this Relation (Total: {{ $relation->documents->count() }})</h3>

    @if($relation->documents->isEmpty())
        <p>No documents found for this relation.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Doc ID</th>
                    <th>File Type</th>
                    <th>File Description</th>
                    <th>User File Name</th>
                    <th>URL</th>
                    <th>Is Disabled</th>
                </tr>
            </thead>
            <tbody>
                @foreach($relation->documents as $document)
                    <tr>
                        <td>{{ $document->doc_id }}</td>
                        <td>{{ $document->file_type }}</td>
                        <td>{{ $document->uploaded_file_desc }}</td>
                        <td>{{ $document->user_file_name }}</td>
                        <td>
                            @if($document->url)
                                <a href="{{ $document->url }}" target="_blank">View File</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $document->is_disabled ? 'Yes' : 'No' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('relation.index') }}" class="btn btn-secondary mt-3">Back to Relations List</a>
@endsection
