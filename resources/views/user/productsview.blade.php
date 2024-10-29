@extends('welcome')

@section('content')
<style>
    body {
        background-color: #f4f4f4;
        color: #333;
        font-family: 'Roboto', sans-serif;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 2rem;
    }

    .product-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .product-image {
        position: relative;
        overflow: hidden;
        aspect-ratio: 1 / 1;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .product-card:hover .product-image img {
        transform: scale(1.05);
    }

    .stock-popup {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .card-text {
        color: #666;
        margin-bottom: 1rem;
    }

    .btn-primary {
        background-color: #3498db;
        border: none;
        border-radius: 20px;
        padding: 0.5rem 1rem;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #2980b9;
    }

    .filter-form {
        background-color: #fff;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .filter-form select {
        border-radius: 20px;
        border: 1px solid #ddd;
    }

    .filter-form button {
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 20px;
        padding: 0.5rem 1.5rem;
        transition: background-color 0.3s;
    }

    .filter-form button:hover {
        background-color: #2980b9;
    }
</style>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-12 mb-4">
            <form action="{{ route('user.product.filter') }}" method="GET" class="filter-form">
                <div class="form-row align-items-center">
                    <div class="col-md-4 mb-2">
                        <label for="category">Category</label>
                        <select class="form-control" id="category" name="category">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="product-grid">
        @foreach($products as $product)
        <div class="product-card">
            <div class="product-image">
                <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->product_name }}">
                <div class="stock-popup">Stock: {{ $product->stock }}</div>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $product->product_name }}</h5>
                <p class="card-text">${{ number_format($product->price, 2) }}</p>
                <div class="mb-2">
                    <span class="badge bg-secondary me-1"> Category:{{ $product->category->name }}</span>
                    <span class="badge bg-secondary me-1">SubCategory:{{ $product->subcategory->name }}</span>
                </div>
                <a href="{{ route('Detailproducts', $product->id) }}" class="btn btn-primary w-100">View Details</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection