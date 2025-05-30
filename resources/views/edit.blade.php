@extends('layout')

@section('title')
    Update Document Details
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>There were some errors with your input:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('document.update', $document->doc_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('forms.form', ['document' => $document])
    </form>
@endsection