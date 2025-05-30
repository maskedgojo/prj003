<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    
        <title>@yield('title', 'Incident Report')</title>
</head>
<body>
   <nav class="navbar navbar-expand-lg custom-navbar shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-primary" href="">
            <i class="bi bi-folder2-open"></i> Navbar
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('document.index') }}">
                        <i class="bi bi-link-45deg"></i> Documents
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('relation.index') }}">
                        <i class="bi bi-link-45deg"></i> Relations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('document.create') }}">
                        <i class="bi bi-plus-circle"></i> Add New Document
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('relation.create') }}">
                        <i class="bi bi-plus-circle"></i> Add New Relation
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    <div class="container py-4">
        <div class="bg-success-subtle text-center rounded p-3 mb-3 shadow-sm">
            <h2 class="text-success">Report</h2>
        </div>

        <div class="bg-warning-subtle rounded p-3 mb-4 shadow-sm">
            <h4 class="text-dark">@yield('title')</h4>
        </div>

        @if (session('status'))
            <div id="successMessage" class="alert alert-success">
                {{ session('status') }}
            </div>

            <script>
                setTimeout(function () {
                    document.getElementById('successMessage').style.display = 'none';
                    window.location.href = "{{ url('/documents') }}"; // Redirect to index
                }, 3000); // 3 seconds
            </script>
        @endif

        @yield('content')
    </div>
</body>
</html>
