<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'E-Commerce')</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div id="navbar">
    <navbar-component 
        :cart-count="{{ $cartCount ?? 0 }}" 
        :show-search='@json(request()->routeIs("catalogue"))'>
    </navbar-component>

    </div>

    <div class="container">
        <h1 class="mb-4">@yield('page-title')</h1>
        @yield('content')
    </div>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</body>


</html>
