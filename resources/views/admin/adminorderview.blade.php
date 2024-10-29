@extends('admin.index')
@section('content')

@if(session('message'))
<div class="alert alert-success container mt-3">
    {{ session('message') }}
</div>
@endif

<style>
    /* Custom CSS */
    body {
        background-color: #f8f9fa;
    }

    .card {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .table {
        background-color: #ffffff;
    }
    .table th {
        background-color: #0d6efd;
        color: #ffffff;
    }
    .order-details {
        background-color: #f1f3f5;
        border-radius: 0.25rem;
        padding: 1rem;
    }
    .order-details-view {
        display: none;
    }
</style>

<div class="container mt-5">
    <h2 class="mb-4">All Orders</h2>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order Details</th>
                            <th>Order Status</th>
                            <th>Payment Status</th>
                            <th>Shipping Status</th>
                            <th>Total Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <strong>Order ID:</strong> {{ $order->id }}<br>
                                <strong>Customer:</strong> {{ $order->customer_name }}<br>
                                <strong>Phone:</strong> {{ $order->customer_phone_number }}<br>
                                <strong>Date:</strong> {{ $order->created_at->format('d-m-Y') }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->order_status == 'pending' ? 'warning' : ($order->order_status == 'delivering' ? 'info' : 'success') }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'danger' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->shipping_status == 'not_shipped' ? 'warning' : ($order->shipping_status == 'shipped' ? 'info' : 'success') }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->shipping_status)) }}
                                </span>
                            </td>
                            <td>NPR {{ number_format($order->order_total, 2) }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm toggle-details" data-order-id="{{ $order->id }}">View Details</button>
                            </td>
                        </tr>
                        <tr class="order-details-view" id="orderDetails-{{ $order->id }}">
                            <td colspan="6">
                                <div class="order-details mb-4">
                                    <h6>Customer Information</h6>
                                    <p>
                                        <strong>Name:</strong> {{ $order->customer_name }}<br>
                                        <strong>Address:</strong> {{ $order->customer_address }}, {{ $order->customer_town_city }}<br>
                                        <strong>Phone:</strong> {{ $order->customer_phone_number }}<br>
                                        <strong>Note:</strong> {{ $order->customer_note ?? 'N/A' }}
                                    </p>
                                </div>
                                
                                <h6>Order Items</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>vendor name:</th>
                                                <th>Item</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order['order_details'] as $item)
                                            <tr>
                                                <td>{{ $item->vendor_name ?? 'N/A' }}</td>
                                                <td>{{ $item->product_name }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>NPR {{ number_format($item->unit_price, 2) }}</td>
                                                <td>NPR {{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-details').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            toggleOrderDetails(orderId);
        });
    });
});

function toggleOrderDetails(orderId) {
    const detailsRow = document.getElementById('orderDetails-' + orderId);
    if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
        detailsRow.style.display = 'table-row';
    } else {
        detailsRow.style.display = 'none';
    }
}
</script>

@endsection
