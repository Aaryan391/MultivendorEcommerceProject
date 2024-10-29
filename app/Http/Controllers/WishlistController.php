<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    function viewwishlist(){
        $wishlistItems= Wishlist::where('user_id',Auth::id())->get();
        return view('user.userwishlist',compact('wishlistItems'));
    }
    public function toggle(Request $request, Product $product)
    {
        $userId = Auth::id();

        $wishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return back()->with('message','Product removed from wishlist.');
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $product->id,
                'category_id' => $product->category_id,
            ]);
            return back()->with('message','Product added to wishlist.');
        }
    }
    public function removeFromWishlist(Wishlist $wishlistItem)
    {
        // Check if the authenticated user owns the wishlist item
        if (Auth::id() !== $wishlistItem->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the wishlist item
        $wishlistItem->delete();

        return redirect()->back()->with('success', 'Product removed from cart successfully');
    }

}
