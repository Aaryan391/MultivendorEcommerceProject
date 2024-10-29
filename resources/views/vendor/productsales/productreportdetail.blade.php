@extends('vendor.navvendor')
@section('content')
<style>
    .dashboard-container {
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-top: 50px;
    }

    .dashboard-title {
        color: #333;
        font-weight: bold;
        margin-bottom: 30px;
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
    }

    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #007bff;
        color: #ffffff;
        border: none;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }

    .badge-commission {
        background-color: #28a745;
        color: #ffffff;
    }

    @media (max-width: 768px) {
        .dashboard-container {
            margin-top: 20px;
            padding: 15px;
        }
    }
</style>
</head>

<body>
    <div class="container dashboard-container">
        <h2 class="text-center dashboard-title">Product Sales Dashboard</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Product</th>
                        <th>Vendor</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Commission</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productSales as $sale)
                    <tr>
                        <td>{{ $sale->id }}</td>
                        <td>{{ $sale->user_name }}</td>
                        <td>{{ $sale->product_name }}</td>
                        <td>{{ $sale->vendor_name }}</td>
                        <td>{{ $sale->quantity }}</td>
                        <td>NPR {{ number_format($sale->price, 2) }}</td>
                        <td>NPR {{ number_format($sale->total_price, 2) }}</td>
                        <td><span class="badge badge-commission">NPR {{ number_format($sale->commission, 2) }}</span></td>
                        <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endsection