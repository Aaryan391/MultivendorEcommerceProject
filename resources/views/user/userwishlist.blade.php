@extends('welcome')
@section('content')
    @php
        use App\Models\Wishlist;
        use Illuminate\Support\Facades\Auth;

        $wishlistItems = Auth::check() ? Wishlist::where('user_id', Auth::id())->with(['product', 'category'])->get() : collect();
    @endphp
    <style>
        .wishlist-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #ccc;
        }
        .wishlist-table th,
        .wishlist-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
        }
        .wishlist-table thead {
            background-color: #333;
            color: #fff;
        }
        .wishlist-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .wishlist-remove-btn {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .wishlist-remove-btn:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .wishlist-image {
            width: 90px;
            height: 50px;
            object-fit: cover;
        }
        .wishlist-empty {
            margin: 5px 0;
            text-align: center;
            color: white;
            background-color: black;
            padding: 30px;
        }
    </style>

    @if ($wishlistItems->isNotEmpty())
        <table class="table table-striped table-bordered wishlist-table">
            <thead class="thead-dark">
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($wishlistItems as $item)
                    <tr>
                        <td>
                            @if ($item->product && $item->product->product_image)
                                <img src="{{ asset('storage/' . $item->product->product_image) }}" alt="Product Image" class="wishlist-image">
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $item->product->product_name ?? 'N/A' }}</td>
                        <td>{{ $item->category->name ?? 'N/A' }}</td>
                        <td>
                        <a href="{{ route('Detailproducts', $item->product->id) }}" class="btn btn-primary btn-sm">View Details</a><br><br>
                                    <form action="{{ route('wishlist.remove', ['wishlistItem' => $item->id]) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Remove</button>
                                    </form>
                                </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="wishlist-empty">No items found in the wishlist.</p>
    @endif
@endsection