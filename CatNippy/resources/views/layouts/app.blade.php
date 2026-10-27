<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'CatNippy — Cats Love IT!')</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container py-2">
            <a class="navbar-brand catnippy-logo" href="{{ route('home') }}">
                <img
                    src="{{ asset('images/cropped.png') }}"
                    alt="CatNippy"
                >
                Cat<span>Nippy</span>
            </a>

            <div class="d-flex gap-4 align-items-center">
                <a href="{{ route('products.index') }}">Shop</a>
                <a href="{{ route('cart.index') }}">Cart</a>
                <a href="#">Our story</a>

                @auth
                    <a href="{{ route('profile.edit') }}">Account</a>
                    <a class="nav-link" href="{{ route('orders.index') }}">My orders</a>

                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-dark btn-sm">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Log in</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="border-top mt-5 py-4">
        <div class="container d-flex justify-content-between flex-wrap gap-2">
            <span class="fw-bold">CatNippy</span>
            <span class="muted small">
                Cats LOVE IT!
            </span>
        </div>
    </footer>
</body>
</html>
