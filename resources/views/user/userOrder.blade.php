@extends('welcome')

@section('content')
<style>
    .order-history {
        background-color: #f8f9fa;
        padding: 60px 0;
    }

    .order-card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .order-card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .order-header {
        background-color: #4a90e2;
        color: #ffffff;
        padding: 20px 25px;
        font-weight: bold;
        font-size: 1.1em;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .order-body {
        padding: 25px;
    }

    .order-info {
        margin-bottom: 20px;
    }

    .order-info p {
        margin-bottom: 8px;
        color: #555;
        font-size: 0.95em;
    }

    .order-total {
        font-size: 1.3em;
        font-weight: bold;
        color: #28a745;
    }

    .order-details {
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .order-item {
        border-bottom: 1px solid #eee;
        padding: 15px 0;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .item-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .item-name {
        font-weight: 600;
        color: #333;
    }

    .item-price {
        color: #28a745;
        font-weight: 600;
    }

    .badge {
        padding: 8px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #000;
    }

    .badge-success {
        background-color: #28a745;
        color: #fff;
    }

    .badge-info {
        background-color: #17a2b8;
        color: #fff;
    }

    .badge-secondary {
        background-color: #6c757d;
        color: #fff;
    }

    .table {
        margin-top: 20px;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .table td, .table th {
        vertical-align: middle;
    }

    .customer-info {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
    }

    .customer-info h4 {
        margin-bottom: 15px;
        color: #4a90e2;
    }
</style>

@if(auth()->user())
<div class="order-history">
    <div class="container">
        <h2 class="text-center mb-5">Your Order History</h2>
        <div class="row">
            @foreach($orders as $order)
            <div class="col-12 mb-4">
                <div class="order-card">
                    <div class="order-header">
                        <span>Order #{{ $order->id }}</span>
                        <span class="badge badge-{{ $order->order_status == 'pending' ? 'warning' : 'success' }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </div>
                    <div class="order-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="order-info">
                                    <p><i class="far fa-calendar-alt mr-2"></i> <strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                                    <p><i class="fas fa-truck mr-2"></i> <strong>Shipping Status:</strong> <span class="badge badge-{{ $order->shipping_status == 'not_shipped' ? 'secondary' : 'info' }}">{{ ucfirst(str_replace('_', ' ', $order->shipping_status)) }}</span></p>
                                    <p><i class="fas fa-money-bill-wave mr-2"></i> <strong>Payment Status:</strong> <span class="badge badge-{{ $order->payment_status == 'pending' ? 'warning' : 'success' }}">{{ ucfirst($order->payment_status) }}</span></p>
                                    <p><i class="fas fa-credit-card mr-2"></i> <strong>Payment Type:</strong> {{ strtoupper($order->order_payment_type) }}</p>
                                    <p class="order-total"><i class="fas fa-tags mr-2"></i> <strong>Total:</strong> NPR {{ number_format($order->order_total, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="customer-info">
                                    <h4>Customer Information</h4>
                                    <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                                    <p><strong>Address:</strong> {{ $order->customer_address }}</p>
                                    <p><strong>City:</strong> {{ $order->customer_town_city }}</p>
                                    <p><strong>Phone:</strong> {{ $order->customer_phone_number }}</p>
                                    @if($order->customer_company)
                                        <p><strong>Company:</strong> {{ $order->customer_company }}</p>
                                    @endif
                                    @if($order->customer_note)
                                        <p><strong>Note:</strong> {{ $order->customer_note }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="order-details">
                            <h4 class="mb-3">Order Items</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderDetails as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->product->product_name }}</strong><br>
                                            <small>{{ $item->product->brand }} | {{ $item->product->color }} | {{ $item->product->size }}</small>
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>NPR {{ number_format($item->unit_price, 2) }}</td>
                                        <td>NPR {{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Total</strong></td>
                                        <td><strong>NPR {{ number_format($order->order_total, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@else
        <div class="alert alert-danger">Please log in to view your order history
        <a class="btn btn-light" href="/login">Login</a>
        </div>
    @endif
@endsection