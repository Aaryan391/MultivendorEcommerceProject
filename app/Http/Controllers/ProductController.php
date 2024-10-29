<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function productview()
    {
        $products = Product::where('vendor_id', Auth::id())->get();
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('vendor.product.product', compact('products', 'categories', 'subcategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'brand' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'style' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
        ]);

        $imageName = time() . '.' . $request->product_image->getClientOriginalExtension();
        $request->product_image->storeAs('public/images', $imageName);

        $tags = $request->tags ? json_encode(array_map('trim', explode(',', $request->tags))) : null;
        Product::create([
            'product_name' => $request->product_name,
            'product_description' => $request->product_description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'vendor_id' => Auth::id(),
            'product_image' => 'images/' . $imageName,
            'brand' => $request->brand,
            'color' => $request->color,
            'size' => $request->size,
            'material' => $request->material,
            'style' => $request->style,
            'tags' => $tags,
        ]);

        return redirect('/productview')->with('message', 'Product added successfully');
    }

    public function update(Request $request, $id)
    {

        $product = Product::findOrFail($id);
        if ($product->vendor_id !== Auth::id()) {
            return redirect('/productview')->with('error', 'You are not authorized to delete this category');
        }
        $request->validate([
            'edit_product_name' => 'required|string|max:255',
            'edit_product_description' => 'required|string',
            'edit_price' => 'required|numeric',
            'edit_stock' => 'required|integer',
            'edit_category_id' => 'required|exists:categories,id',
            'edit_subcategory_id' => 'required|exists:subcategories,id',
            'edit_product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8000',
            'edit_brand' => 'nullable|string|max:255',
            'edit_color' => 'nullable|string|max:255',
            'edit_size' => 'nullable|string|max:255',
            'edit_material' => 'nullable|string|max:255',
            'edit_style' => 'nullable|string|max:255',
            'edit_tags' => 'nullable|string',
        ]);
        $tags = $request->edit_tags ? json_encode(array_map('trim', explode(',', $request->edit_tags))) : null;

        $data = [
            'product_name' => $request->edit_product_name,
            'product_description' => $request->edit_product_description,
            'price' => $request->edit_price,
            'stock' => $request->edit_stock,
            'category_id' => $request->edit_category_id,
            'subcategory_id' => $request->edit_subcategory_id,
            'brand' => $request->edit_brand,
            'color' => $request->edit_color,
            'size' => $request->edit_size,
            'material' => $request->edit_material,
            'style' => $request->edit_style,
            'tags' => $tags,
        ];

        if ($request->hasFile('edit_product_image')) {
            Storage::delete('public/' . $product->product_image);
            $imageName = time() . '.' . $request->edit_product_image->getClientOriginalExtension();
            $request->edit_product_image->storeAs('public/images', $imageName);
            $data['product_image'] = 'images/' . $imageName;
        }

        $product->update($data);

        return redirect('/productview')->with('message', 'Product updated successfully');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->vendor_id !== Auth::id()) {
            return redirect('/productview')->with('error', 'You are not authorized to delete this category');
        }
        Storage::delete('public/' . $product->product_image);
        $product->delete();
        return redirect('/productview')->with('message', 'Product deleted successfully');
    }

    public function getSubcategories(Request $request)
    {
        $category_id = $request->input('category_id');
        $subcategories = Subcategory::where('category_id', $category_id)->get();

        return response()->json($subcategories);
    }
    public function userproductview()
    {
        $products = Product::all();
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('user/productsview', compact('products', 'categories', 'subcategories'));
    }
    public function productdetail($id)
    {
        $product = Product::findOrFail($id);
        $similarProducts = $this->getSimilarProducts($product);
        return view('user/productdetail', compact('product', 'similarProducts'));
    }
    private function getSimilarProducts($product)
    {
        return Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(5)
            ->get();
    }
    public function userProductFilter(Request $request)
    {
        $categoryId = $request->input('category');

        $query = Product::query();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->get();
        $categories = Category::all();

        return view('user/productsview', compact('products', 'categories'));
    }
    public function showFeaturedProducts()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $products = Product::with(['category', 'subcategory', 'vendor'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
        // Check if the user is logged in
        if (Auth::check()) {
            $userId = Auth::id();
            // Fetch recommendations based on user ID
            $recommendedProducts = $this->getRecommendations($userId);
            $recommendedProductscol= $this->getRecommendationscol($userId);
        } else {
            $recommendedProducts = []; // or fetch some default products
            $recommendedProductscol = [];
        }
        return view('user/featuredproduct', compact('products', 'recommendedProducts','recommendedProductscol','categories','subcategories'));
    }
    //content based algorithm
    private function getRecommendations($userId)
    {
        $userOrders = Order::where('user_id', $userId)->with('orderDetails.product')->get();

        // Collect unique category IDs from the products in user's orders
        $categories = $userOrders->flatMap(function ($order) {
            return $order->orderDetails->pluck('product.category_id');
        })->unique();

        // Fetch products that belong to the user's purchased categories
        return Product::whereIn('category_id', $categories)
            ->where('stock', '>', 0)                  // Ensure the product is in stock
            ->orderBy('popularity_score', 'desc')     // Order by popularity score
            ->limit(5)                                // Limit the result to 5 products
            ->get();
    }
    //Collaborative filtering algorithm
    private function getRecommendationscol($userId)
    {
        // Get the current user's purchased products
        $userPurchases = Order::where('orders.user_id', $userId)
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->pluck('order_details.product_id')
            ->toArray();    
    
        // Find similar users who purchased the same products as the current user
        $similarUsers = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->whereIn('order_details.product_id', $userPurchases)
            ->where('orders.user_id', '!=', $userId)  // Exclude the current user
            ->pluck('orders.user_id')
            ->unique()
            ->toArray();
    
        // Get products purchased by similar users but not by the current user
        $recommendedProductscol = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
    ->join('products', 'order_details.product_id', '=', 'products.id')
    ->whereIn('orders.user_id', $similarUsers)  // Use orders.user_id
    ->whereNotIn('order_details.product_id', $userPurchases)  // Exclude already purchased products
    ->select(
        'products.id', 
        'products.product_name', 
        'products.price', 
        'products.vendor_id', 
        'products.product_image', 
        'products.product_description', 
        'products.brand', 
        'products.color', 
        'products.size', 
        'products.material', 
        'products.style', 
        'products.popularity_score', 
        'products.average_rating'
    )
    ->groupBy(
        'products.id', 
        'products.product_name', 
        'products.price', 
        'products.vendor_id', 
        'products.product_image', 
        'products.product_description', 
        'products.brand', 
        'products.color', 
        'products.size', 
        'products.material', 
        'products.style', 
        'products.popularity_score', 
        'products.average_rating'
    )
    ->orderByRaw('COUNT(order_details.product_id) DESC')  // Order by most purchased by similar users
    ->limit(5)
    ->get();

    
        return $recommendedProductscol;
    }
    public function search(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
        ]);
        // Check if none of the search parameters are provided
        if (!$request->filled('search') && !$request->filled('category_id') && !$request->filled('subcategory_id')) {
            return redirect()->back()->withErrors(['search' => 'At least one search parameter is required.']);
        }
        $query = Product::query();
    
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('product_name', 'like', '%' . $searchTerm . '%');
        }
    
        if ($request->filled('category_id')) {
            $categoryID = $request->input('category_id');
            $query->where('category_id', $categoryID);
        }
    
        if ($request->filled('subcategory_id')) {
            $subcategoryID = $request->input('subcategory_id');
            $query->where('subcategory_id', $subcategoryID);
        }
    
        $products = $query->get();

        return view('user.searchresult', compact('products'));
    }
    public function getSsubcategories(Request $request)
    {
        $category_id = $request->input('category_id');
        $subcategories = Subcategory::where('category_id', $category_id)->get();

        return response()->json($subcategories);
    }
}
