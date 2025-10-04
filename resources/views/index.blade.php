@extends('layouts.app')

@section('title', 'My Orders')
@section('page-title', 'My Orders')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="text-center py-5">
            <div class="mb-4" style="font-size: 4rem;">📦</div>
            <h3 class="mb-3">No orders yet</h3>
            <p class="text-muted mb-4">You haven't placed any orders yet.</p>
            <a href="/catalogue" class="btn btn-primary">Start Shopping</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->created_at->format('M d, Y') }}<br>
                            <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                        </td>
                        <td>{{ $order->customer_name }}</td>
                        <td>
                            <ul class="mb-0 ps-3">
                                @foreach($order->items as $item)
                                    <li>
                                        <strong>{{ $item->product->name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            Qty: {{ $item->quantity }} × RM {{ number_format($item->price, 2) }}
                                        </small>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <strong class="text-primary">RM {{ number_format($order->total_amount, 2) }}</strong>
                        </td>
                        <td>
                            <span class="badge 
                                @if($order->status === 'pending') bg-warning text-dark
                                @elseif($order->status === 'completed') bg-success
                                @else bg-secondary
                                @endif
                            ">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}" class="d-flex gap-2">
                                @csrf
                                <select name="status" class="form-select form-select-sm" style="width: auto;">
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
        </div>

        <div class="mt-3">
            <p class="text-muted">Total Orders: <strong>{{ $orders->count() }}</strong></p>
        </div>
    @endif
@endsection 