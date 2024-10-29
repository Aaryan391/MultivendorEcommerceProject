@extends('welcome')
@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4">Search Results</h2>
    @if(isset($query))
        <p class="text-center mb-4">Showing results for: <strong>{{ $query }}</strong></p>
    @endif

    @if($products->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
            No products found.
        </div>
    @else
        <div class="row">
            @foreach($products as $product)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card shadow-sm border-light rounded">
                        <img src="{{ asset('storage/' . $product->product_image) }}" class="card-img-top" alt="{{ $product->product_name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->product_name }}</h5>
                            <p class="card-text">{{ Str::limit($product->product_description, 100) }}</p>
                            <p class="card-text fw-bold">Price: ${{ number_format($product->price, 2) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('Detailproducts', $product->id) }}" class="btn btn-primary">View Details</a>
                                <small class="text-muted">Added on {{ $product->created_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
