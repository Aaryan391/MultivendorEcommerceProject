@extends('welcome')
@section('content')
<div class="container mt-4">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @elseif(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(auth()->user())
        @if($cartitems->isEmpty())
            <div class="alert alert-info">Your cart is empty.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Product Image</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Unit Price (NPR)</th>
                            <th scope="col">Total Price (NPR)</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartitems as $key => $cartItem)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>
                                    <img src="{{ asset('storage/' . $cartItem->product->product_image) }}" alt="" class="img-thumbnail" style="max-width: 100px;">
                                </td>
                                <td>{{ $cartItem->product->product_name }}</td>
                                <td>
                                    <form action="{{ route('cart.update', ['cartItem' => $cartItem->id]) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="input-group input-group-sm">
                                            <input type="number" name="quantity" value="{{ $cartItem->quantity }}" class="form-control" min="1">
                                            <button type="submit" class="btn btn-outline-primary">Update</button>
                                        </div>
                                    </form>
                                </td>
                                <td>{{ $cartItem->unit_price }}</td>
                                <td>{{ $cartItem->quantity * $cartItem->unit_price }}</td>
                                <td>
                                    <form action="{{ route('cart.remove', ['cartItem' => $cartItem->id]) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('checkout') }}" class="btn btn-primary">Proceed to Checkout</a>
            </div>
        @endif
    @else
        <div class="alert alert-danger">Please log in to view your cart.
        <a class="btn btn-light" href="/login">Login</a>
        </div>
    @endif
</div>

@endsection