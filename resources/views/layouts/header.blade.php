<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('catalogue') }}">E-Commerce</a>
        <div class="d-flex">
            <a href="{{ route('catalogue') }}" class="btn btn-outline-secondary me-2">Catalogue</a>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary me-2">Orders</a>
            <a href="{{ route('cart.index') }}" class="btn btn-outline-primary position-relative">
                🛒 Cart
                @if($cartCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
        </div>
    </div>
</nav>
