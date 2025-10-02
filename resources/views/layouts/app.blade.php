<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'E-Commerce')</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    {{-- Navbar with Search + Cart + Orders --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('catalogue') }}">E-Commerce</a>
            
            <div class="d-flex align-items-center ms-auto">
                {{-- Show search bar only in catalogue page --}}
                @if(request()->routeIs('catalogue'))
                    <form action="{{ route('catalogue') }}" method="GET" class="d-flex me-3">
                        <input type="text" name="search" class="form-control form-control-sm me-2" 
                               placeholder="Search products..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-sm btn-primary">Search</button>
                    </form>
                @endif

                <a href="{{ route('catalogue') }}" class="btn btn-outline-secondary text-dark me-2">Catalogue</a>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary text-dark me-2">Orders</a>
                <a href="{{ route('cart.index') }}" class="btn btn-outline-primary position-relative">
                    🛒
                    @if($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="mb-4">@yield('page-title')</h1>

        @yield('content')
    </div>

</body>
</html>
