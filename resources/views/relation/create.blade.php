@extends('layout')

@section('title')
    Add New relation
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

    <form action="{{ route('relation.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('forms.rform', ['relation' => null])
    </form>
@endsection
