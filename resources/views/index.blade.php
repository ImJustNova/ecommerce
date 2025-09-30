<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h1>Orders</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($orders->isEmpty())
        <div class="alert alert-info">No orders found.</div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Items</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->status }}</td>
                    <td>
                        <ul>
                            @foreach($order->items as $item)
                                <li>{{ $item->product->name }} (x{{ $item->quantity }}) - ${{ number_format($item->price,2) }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}">
                            @csrf
                            <select name="status" class="form-select form-select-sm d-inline w-auto">
                                <option value="pending" {{ $order->status=='pending'?'selected':'' }}>Pending</option>
                                <option value="completed" {{ $order->status=='completed'?'selected':'' }}>Completed</option>
                                <option value="cancelled" {{ $order->status=='cancelled'?'selected':'' }}>Cancelled</option>
                            </select>
                            <button class="btn btn-sm btn-primary">Update</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
</body>
</html>
