@extends('layout')

@section('title', 'Document Details')

@section('content')
    <h1>Document Details (ID: {{ $document->doc_id }})</h1>

    <div class="card p-3">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>File Type</th>
                    <td>{{ $document->file_type }}</td>
                </tr>
                <tr>
                    <th>Table Name</th>
                    <td>{{ $document->table_name }}</td>
                </tr>
                <tr>
                    <th>Reference ID</th>
                    <td>{{ $document->ref_id }}</td>
                </tr>
                <tr>
                    <th>User File Name</th>
                    <td>
                            @if($document->random_file_name || $document->user_file_name)
                                @php
                                    $fileName = $document->random_file_name ?: $document->user_file_name;
                                    $displayName = $document->user_file_name ?: $fileName;
                                    $fileUrl = asset('documents/' . $fileName);
                                @endphp
                                <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-success" title="Open {{ $displayName }}">
                                    <i class="bi bi-file-earmark-text"></i> {{ Str::limit($displayName, 20) }}
                                </a>
                            @else
                                <span class="text-muted">No File</span>
                            @endif
                    </td>
                </tr>
                <tr>
                    <th>File Description</th>
                    <td>{{ $document->uploaded_file_desc }}</td>
                </tr>
                <tr>
                    <th>URL</th>
                    <td>
                        @if($document->url)
                            <a href="{{ $document->url }}" target="_blank">{{ $document->url }}</a>
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Publication</th>
                    <td>{{ $document->publication }}</td>
                </tr>
                <tr>
                    <th>Is Disabled</th>
                    <td>
                        <span class="badge {{ $document->is_disabled ? 'bg-danger' : 'bg-success' }}">
                            {{ $document->is_disabled ? 'Disabled' : 'Active' }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>

        <a href="{{ route('document.index') }}" class="btn btn-secondary mt-3">Back to Documents List</a>
    </div>
@endsection
