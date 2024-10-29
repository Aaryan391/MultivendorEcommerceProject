<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    function Cartview()
    {
        $cartitems = Cart::where('user_id', Auth::id())->get();
        return view('user/usercart', compact('cartitems'));
    }
    public function addToCart(Request $request, Product $product)
{
    // Validate the request
    $request->validate([
        'quantity' => 'required|numeric|min:1',
    ]);

    // Get the authenticated user
    $user = Auth::user();

    // Check if the product is already in the cart
    $cartItem = Cart::where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->first();

    // Calculate the total quantity including the existing quantity in the cart
    $totalQuantityInCart = $cartItem ? $cartItem->quantity : 0;
    $requestedQuantity = $request->input('quantity');
    $newTotalQuantity = $totalQuantityInCart + $requestedQuantity;

    // Check if the new total quantity exceeds the available stock
    if ($newTotalQuantity > $product->stock) {
        return redirect()->back()->with('error', 'Total quantity in cart exceeds available stock');
    }

    if ($cartItem) {
        // Update the quantity if the product is already in the cart
        $cartItem->update([
            'quantity' => $newTotalQuantity,
        ]);
    } else {
        // Add a new item to the cart
        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'category_id' => $product->category_id,
            'subcategory_id' => $product->subcategory_id,
            'quantity' => $requestedQuantity,
            'unit_price' => $product->price,
        ]);
    }

    return redirect()->back()->with('success', 'Product added to cart successfully');
}

    public function removeFromCart(Cart $cartItem)
    {
        // Check if the authenticated user owns the cart item
        if (Auth::id() !== $cartItem->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the cart item
        $cartItem->delete();

        return redirect()->back()->with('success', 'Product removed from cart successfully');
    }

    public function updateCartQuantity(Request $request, Cart $cartItem)
    {
        // Validate the request
        $request->validate([
            'quantity' => 'required|numeric|min:1',
        ]);

        $requestedQuantity = $request->input('quantity');
        if ($cartItem->product->stock < $requestedQuantity) {
            return redirect()->back()->with('error', 'Requested quantity exceeds available stock');
        }

        // Update the quantity
        $cartItem->update([
            'quantity' => $requestedQuantity,
        ]);

        return redirect()->back()->with('success', 'Quantity updated successfully');
    }
}