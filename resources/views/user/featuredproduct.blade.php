@extends('welcome')
@section('content')
<style>
    /* our product css*/
    .product-section {
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        padding: 3rem 0;
    }
    .section-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 2rem;
        position: relative;
        display: inline-block;
    }
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 3px;
        background-color: #007bff;
    }
    .product-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }
    .product-image {
        height: 200px;
        object-fit: cover;
        transition: all 0.3s ease;
    }
    .product-card:hover .product-image {
        transform: scale(1.1);
    }
    .card-body {
        padding: 1.5rem;
    }
    .card-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .card-text {
        color: #6c757d;
        font-size: 0.9rem;
    }
    .price {
        font-weight: 600;
        color: #28a745;
    }
    .btn-primary {
        background-color: #007bff;
        border: none;
        border-radius: 50px;
        padding: 0.5rem 1.5rem;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }
    /*end css */
</style>

<div class="container my-5">
    <div class="product-section">
        <h2 class="text-center section-title mb-5">Our Latest Collection</h2>
        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card product-card h-100">
                    <div class="overflow-hidden">
                        <img src="{{ asset('storage/' . $product->product_image) }}" class="card-img-top product-image" alt="{{ $product->product_name }}">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->product_name }}</h5>
                        <p class="card-text flex-grow-1">{{ Str::limit($product->product_description, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="price">${{ number_format($product->price, 2) }}</span>
                            <a href="{{ route('Detailproducts', $product->id) }}" class="btn btn-primary">View Detail</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Recommended Products Section -->
@if(Auth::check() && !$recommendedProducts->isEmpty())
    <div class="my-5">
        <h2 class="text-center section-title mb-5">Recommended for You</h2>
        <div class="row g-4">
            @foreach($recommendedProducts as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card product-card h-100">
                    <div class="overflow-hidden">
                        <img src="{{ asset('storage/' . $product->product_image) }}" class="card-img-top product-image" alt="{{ $product->product_name }}">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->product_name }}</h5>
                        <p class="card-text flex-grow-1">{{ Str::limit($product->product_description, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="price">${{ number_format($product->price, 2) }}</span>
                            <a href="{{ route('Detailproducts', $product->id) }}" class="btn btn-primary">View Detail</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endif

@if(Auth::check() && !$recommendedProductscol->isEmpty())
    <div class="container">
        <h1>Product you may like</h1>
        <div class="row">
            @foreach($recommendedProductscol as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card product-card h-100">
                    <div class="overflow-hidden">
                        <img src="{{ asset('storage/' . $product->product_image) }}" class="card-img-top product-image" alt="{{ $product->product_name }}">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->product_name }}</h5>
                        <p class="card-text flex-grow-1">{{ Str::limit($product->product_description, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="price">${{ number_format($product->price, 2) }}</span>
                            <a href="{{ route('Detailproducts', $product->id) }}" class="btn btn-primary">View Detail</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endif

@endsection
