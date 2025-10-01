@extends('layouts.app')

@section('title', 'Product Catalogue')
@section('page-title', 'Product Catalogue')

@section('content')
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
@endsection
