<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel Like & Dislike</title>

    <!-- Bootstrap CDN (NO VITE, NO NPM) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">Laravel App</a>

        <div class="ms-auto">
            @auth
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-danger btn-sm">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm ms-2">Register</a>
            @endauth
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body text-center">
            <h2 class="mb-3">Laravel Like & Dislike System 👍👎</h2>
            <p class="text-muted">
                Simple Laravel 12 project without Vite.<br>
                Perfect for learning and interviews.
            </p>

            <a href="/posts" class="btn btn-primary mt-3">
                View Posts
            </a>
        </div>
    </div>
</div>

</body>
</html>
