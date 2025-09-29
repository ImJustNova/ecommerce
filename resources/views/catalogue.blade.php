<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Catalogue</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Product Catalogue</h1>
        <div>
            <a href="/cart" class="btn btn-outline-primary">Cart</a>
            <a href="/orders" class="btn btn-outline-secondary">Orders</a>
        </div>
    </div>

    @if($products->isEmpty())
        <div class="alert alert-info">No products found. Seed some sample products.</div>
    @else
        <div class="row g-3">
            @foreach($products as $product)
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">Price: ${{ number_format($product->price, 2) }}</p>
                            <p class="card-text">Stock: {{ $product->stock ?? 'N/A' }}</p>
                            <!-- Add-to-cart route will be implemented later -->
                            <form method="POST" action="{{ route('cart.add', $product->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

</body>
</html>
