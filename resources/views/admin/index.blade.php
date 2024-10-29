<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopSphere</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item active"><a class="nav-link" href="/admin/dashboard">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/vendor-requests">Vendor Request</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/users">User Manage</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/orders">View orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
                </ul>
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
                    <img src="{{ asset('storage/images/cashondelivery.png') }}" alt="cash on delievry">
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>