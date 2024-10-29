@extends('welcome')
@section('content')
<style>
    .product-container {
        background-color: #f8f9fa;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .product-image {
        height: 400px;
        object-fit: cover;
        border-radius: 15px 0 0 15px;
    }
    .product-details {
        padding: 2rem;
    }
    .product-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    .product-description {
        font-size: 1.1rem;
        line-height: 1.6;
        color: #6c757d;
    }
    .product-meta {
        background-color: #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .product-meta p {
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    .custom-btn {
        border-radius: 25px;
        padding: 10px 20px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .wishlist-icon {
        cursor: pointer;
        font-size: 1.5em;
        transition: color 0.3s ease;
    }
    .wishlist-icon:hover {
        color: #dc3545;
    }
    .similar-products {
        background-color: #fff;
        border-radius: 15px;
        padding: 2rem;
        margin-top: 3rem;
    }
    .similar-product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .similar-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>

<div class="container mt-5">
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('success') || session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') ?? session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="product-container">
        <div class="row g-0">
            <div class="col-md-6">
                <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="img-fluid product-image">
            </div>
            <div class="col-md-6 product-details">
                <h1 class="product-title">{{ $product->product_name }}</h1>
                <p class="product-description mb-4">{{ $product->product_description }}</p>
                
                <div class="product-meta">
                    @if($product->price)
                    <p><strong>Price:</strong> ${{ $product->price }}</p>
                    @endif
                    @if($product->stock)
                    <p><strong>Stock:</strong> {{ $product->stock }}</p>
                    @endif
                    @if($product->brand)
                    <p><strong>Brand:</strong> {{ $product->brand }}</p>
                    @endif
                    @if($product->color)
                    <p><strong>Color:</strong> {{ $product->color }}</p>
                    @endif
                    @if($product->size)
                    <p><strong>Size:</strong> {{ $product->size }}</p>
                    @endif
                    @if($product->material)
                    <p><strong>Material:</strong> {{ $product->material }}</p>
                    @endif
                    @if($product->style)
                    <p><strong>Style:</strong> {{ $product->style }}</p>
                    @endif
                    @if($product->average_rating)
                    <p><strong>Rating:</strong> {{ $product->average_rating }} / 5</p>
                    @endif
                </div>

                <div class="mb-3">
                    <span class="badge bg-primary me-1">{{ $product->category->name }}</span>
                    <span class="badge bg-secondary me-1">{{ $product->subcategory->name }}</span>
                </div>

                @if($product->tags)
                <div class="mb-4">
                    <strong>Tags:</strong>
                    @foreach(is_array($product->tags) ? $product->tags : json_decode($product->tags) as $tag)
                    <span class="badge bg-info me-1">{{ $tag }}</span>
                    @endforeach
                </div>
                @endif
                <small class="text-muted">Added on {{ $product->created_at->format('M d, Y') }}</small>
                <div class="d-flex align-items-center mb-4">
                    <form method="POST" action="{{ route('cart.add', $product->id) }}" class="d-flex align-items-center">
                        @csrf
                        <div class="input-group me-3" style="width: 120px;">
                            <span class="input-group-text">Qty</span>
                            <input type="number" class="form-control" name="quantity" value="1" min="1" max="{{ $product->stock }}">
                        </div>
                        <button type="submit" class="btn btn-primary custom-btn me-3">Add to Cart</button>
                    </form>
                    <form action="{{ route('wishlist.toggle', ['product' => $product->id]) }}" method="POST" class="wishlist-form">
                        @csrf
                        <button type="submit" class="btn btn-link p-0">
                            <i class="wishlist-icon fa-heart @if($product->isInWishlist(auth()->id())) fas text-danger @else far @endif"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($similarProducts->count() > 0)
    <div class="similar-products">
        <h2 class="mb-4">Similar Products</h2>
        <div class="row">
            @foreach($similarProducts as $similarProduct)
            <div class="col-md-3 mb-4">
                <div class="card h-100 similar-product-card">
                    <img src="{{ asset('storage/' . $similarProduct->product_image) }}" class="card-img-top" alt="{{ $similarProduct->product_name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $similarProduct->product_name }}</h5>
                        <p class="card-text">${{ $similarProduct->price }}</p>
                        <a href="{{ route('Detailproducts', $similarProduct->id) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
    $(document).ready(function() {
        $('.wishlist-form').on('submit', function(e) {
            e.preventDefault();
            let $form = $(this);
            let $icon = $form.find('.wishlist-icon');
            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                success: function(response) {
                    if (response.success) {
                        if (response.added) {
                            $icon.removeClass('far').addClass('fas text-danger');
                        } else {
                            $icon.removeClass('fas text-danger').addClass('far');
                        }
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    toastr.error('An error occurred');
                }
            });
        });
    });
</script>
@endsection