<?php

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

if (Auth::check()) {
    $carts = Cart::where('user_id', Auth::id())->get();
    $totalCartItems = $carts->sum('quantity');
} else {
    $totalCartItems = 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopSphere</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .top-bar {
            background-color: #2c3e50;
            padding: 10px 0;
        }

        .navbar {
            background-color: #4a2c2c;
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: bold;
            color: #ecf0f1 !important;
            font-size: 24px;
        }

        .nav-link {
            color: #bdc3c7 !important;
            margin-right: 15px;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #ecf0f1 !important;
        }

        .active .nav-link {
            color: #3498db !important;
        }

        .logo img {
            height: 40px;
        }

        .user-actions a {
            color: #bdc3c7;
            margin-left: 15px;
            text-decoration: none;
        }

        .user-actions a:hover {
            color: #ecf0f1;
        }

        .cart-indicator {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }

        footer {
            background: #f8f9fa;
            padding-top: 2rem;
            padding-bottom: 1rem;
        }

        footer a {
            text-decoration: none;
            color: #343a40;
        }

        footer a:hover {
            color: #007bff;
        }

        footer .fab {
            font-size: 1.5em;
            margin: 0 10px;
        }

        .payment-options img {
            max-width: 60px;
            margin: 0 10px;
        }

        @media (max-width: 991px) {
            .navbar-nav {
                background-color: #34495e;
                padding: 10px;
                border-radius: 5px;
            }
        }

        /* css of search bar*/
        .search-container {
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .advanced-search {
            display: none;
        }

        .advanced-search.show {
            display: block;
        }

        /* Adjust button size and styles */
        .btn-primary {
            padding: 0 1rem;
            /* Adjust padding */
        }

        .btn-success {
            padding: 0 1rem;
            /* Adjust padding */
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <div class="top-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="company-name">
                <a class="navbar-brand" href="#">ShopSphere</a>
            </div>
            <div class="login-register">
                @if(auth()->user())
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Log Out</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                <a class="nav-link" href="login">Login/Register</a>
                @endif
            </div>
        </div>
    </div>
    <!-- Advanced Search Modal -->
    <div class="container mt-1">
    <div class="row">
        <div class="col-md-12 d-flex justify-content-center">
            <div class="search-container w-75">
                <form action="{{ route('search') }}" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="search" placeholder="Search products by name" aria-label="Search products">
                        
                        <button class="btn btn-primary" type="submit">Search</button>
                        
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#advancedSearchModal">
                            Advanced
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="advancedSearchModal" tabindex="-1" aria-labelledby="advancedSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="advancedSearchModalLabel">Advanced Search</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="advanced-search-form" action="{{ route('search') }}" method="GET">
                    <!-- Category Dropdown -->
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Select Category</label>
                        <select class="form-select" name="category_id" id="category_id">
                            <option value="" selected>Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Subcategory Dropdown -->
                    <div class="mb-3">
                        <label for="subcategory_id" class="form-label">Select Subcategory</label>
                        <select class="form-select" name="subcategory_id" id="subcategory_id">
                            <option value="" selected>Select Subcategory</option>
                            <!-- Subcategories will be dynamically loaded here based on selected category -->
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id ="search-button" >Save Changes</button>
            </div>
        </div>
    </div>
</div>


    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item active"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('userproductview')}}">Product</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('cartview') }}">Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('checkout') }}">Checkout</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('userOrder') }}">Order</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
                </ul>
                <div class="user-actions d-flex align-items-center">
                    <a href="/wishlist" class="position-relative">
                        <i class="fas fa-heart"></i>
                    </a>
                    <a href="/cart" class="position-relative ms-3">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-indicator">{{ $totalCartItems }}</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <main class="container my-4">
        @yield('content')
    </main>
    <footer class="bg-light text-dark">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold">MultiVendor Marketplace</h5>
                    <p><i class="fas fa-map-marker-alt mr-2"></i>Itahari-2, Itahari, Nepal</p>
                    <p><i class="fas fa-envelope mr-2"></i>support@multivendor.com</p>
                    <p><i class="fas fa-phone mr-2"></i>+977 9800000000</p>
                </div>
                <div class="col-md-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold">For Shoppers</h5>
                    <p><a href="{{ route('userOrder') }}" class="text-dark">Track Your Order</a></p>
                </div>
                <div class="col-md-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold">For Vendors</h5>
                    <p><a href="{{route('vendordashboard') }}" class="text-dark">Sell on MultiVendor</a></p>
                </div>
                <div class="col-md-3">
                    <p><a href="{{ route('userproductview')}}" class="text-dark">Naviagate To Categories</a></p>
                </div>
            </div>
            <hr>
            <div class="row align-items-center text-center">
                <div class="col-md-12 mb-3">
                    <a href="#" class="text-dark mx-2"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-dark mx-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-dark mx-2"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-dark mx-2"><i class="fab fa-pinterest"></i></a>
                    <a href="#" class="text-dark mx-2"><i class="fab fa-youtube"></i></a>
                </div>
                <div class="col-md-12 payment-options mb-3">
                    <img src="{{ asset('storage/images/khalti.png') }}" alt="Khalti">
                    <img src="{{ asset('storage/images/cashondelivery.png') }}" alt="cash on delivery">
                </div>
                <div class="col-md-12">
                    <p class="mb-0">Copyright &copy; 2024 MultiVendor Marketplace. All rights reserved.
                        <a href="#" class="text-dark">Terms of Service</a> |
                        <a href="#" class="text-dark">Privacy Policy</a> |
                        <a href="#" class="text-dark">Seller Policy</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            function loadSelections() {
        var savedCategoryId = localStorage.getItem('selectedCategoryId');
        var savedSubcategoryId = localStorage.getItem('selectedSubcategoryId');

        if (savedCategoryId) {
            $('select[name="category_id"]').val(savedCategoryId).trigger('change');
        }
        if (savedSubcategoryId) {
            $('select[name="subcategory_id"]').val(savedSubcategoryId);
        }
    }
            // Event listener for the category dropdown change
            $('select[name="category_id"]').change(function() {
                var categoryId = $(this).val();

                if (categoryId) {
                    // Make an AJAX request to get subcategories for the selected category
                    $.ajax({
                        url: '{{ route("get-subcategories") }}', // Route for fetching subcategories
                        method: 'GET',
                        data: {
                            category_id: categoryId
                        },
                        success: function(response) {
                            // Clear existing subcategories
                            $('select[name="subcategory_id"]').empty();
                            $('select[name="subcategory_id"]').append('<option value="" selected>Select Subcategory</option>');

                            // Add the retrieved subcategories to the dropdown
                            $.each(response, function(index, subcategory) {
                                $('select[name="subcategory_id"]').append('<option value="' + subcategory.id + '">' + subcategory.name + '</option>');
                            });
                        },
                        error: function(error) {
                            console.log('Error fetching subcategories: ' + error.responseText);
                        }
                    });
                } else {
                    // Clear the subcategory dropdown if no category is selected
                    $('select[name="subcategory_id"]').empty();
                    $('select[name="subcategory_id"]').append('<option value="" selected>Select Subcategory</option>');
                }
            });
            $('select[name="subcategory_id"]').change(function() {
        var subcategoryId = $(this).val();

        // Save selected subcategory in local storage
        localStorage.setItem('selectedSubcategoryId', subcategoryId);
    });

    // Load saved selections when modal is opened
    $('#advancedSearchModal').on('show.bs.modal', function() {
        loadSelections();
    });

    // Handle search button click
    $('#search-button').on('click', function() {
        var formData = $('#advanced-search-form').serialize(); // Get the form data
        window.location.href = '{{ route("search") }}?' + formData; // Redirect to the search results page
    });
        });
        
    </script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>