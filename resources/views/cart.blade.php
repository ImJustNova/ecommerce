<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Shopping Cart</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Shopping Cart</h1>
        <div>
            <a href="{{ route('catalogue') }}" class="btn btn-outline-primary">Catalogue</a>
            <a href="{{ route('cart.index') }}" class="btn btn-outline-dark">Cart</a>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Orders</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(empty($cart))
        <div class="alert alert-info">Your cart is empty.</div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>${{ number_format($item['price'], 2) }}</td>
                    <td>
                        <form method="POST" action="{{ route('cart.update', $item['id']) }}" class="d-flex">
                            @csrf
                            <input type="number" name="qty" value="{{ $item['qty'] }}" min="1" class="form-control form-control-sm w-50 me-2">
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                        </form>
                    </td>
                    <td>${{ number_format($item['price'] * $item['qty'], 2) }}</td>
                    <td>
                        <form method="POST" action="{{ route('cart.remove', $item['id']) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center">
            <h4>Total: ${{ number_format($total, 2) }}</h4>
            <div>
                <form method="POST" action="{{ route('cart.clear') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Clear Cart</button>
                </form>
                <form method="POST" action="{{ route('order.place') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">Place Order</button>
                </form>
            </div>
        </div>
    @endif

</div>

</body>
</html>
