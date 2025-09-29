<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Your Cart</h1>
        <div>
            <a href="{{ route('catalogue') }}" class="btn btn-outline-secondary">Continue Shopping</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(empty($cart) || count($cart) == 0)
        <div class="alert alert-info">Your cart is empty.</div>
    @else
        <table class="table">
            <thead>
                <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr>
            </thead>
            <tbody>
                @foreach($cart as $id => $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>${{ number_format($item['price'], 2) }}</td>
                    <td>
                        <form method="POST" action="{{ route('cart.update', $id) }}" class="d-flex">
                            @csrf
                            <input type="number" name="qty" value="{{ $item['qty'] }}" min="0" class="form-control form-control-sm me-2" style="width:80px">
                            <button class="btn btn-sm btn-outline-primary">Update</button>
                        </form>
                    </td>
                    <td>${{ number_format($item['price'] * $item['qty'], 2) }}</td>
                    <td>
                        <form method="POST" action="{{ route('cart.remove', $id) }}">
                            @csrf
                            <button class="btn btn-sm btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                    <td><strong>${{ number_format($total, 2) }}</strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex gap-2">
            <form method="POST" action="{{ route('order.place') }}">
                @csrf
                <button class="btn btn-success">Place Order</button>
            </form>

            <form method="POST" action="{{ route('cart.clear') }}">
                @csrf
                <button class="btn btn-outline-danger">Clear Cart</button>
            </form>
        </div>
    @endif
</div>
</body>
</html>
