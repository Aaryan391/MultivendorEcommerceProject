<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\WishlistController;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard',[HomeController::class,'index']);
    Route::get('admin/dashboard',[HomeController::class,'showgraphdata']);
    Route::get('/admin/vendor-requests', [HomeController::class, 'showVendorRequests'])->name('admin.vendorRequests');
    Route::post('/admin/vendor-requests/{id}/accept', [HomeController::class, 'acceptVendorRequest'])->name('admin.acceptVendorRequest');
    Route::post('/admin/vendor-requests/{id}/decline', [HomeController::class, 'declineVendorRequest'])->name('admin.declineVendorRequest');
    Route::get('/admin/users', [HomeController::class, 'userList'])->name('admin.users');
    Route::get('/admin/orders', [HomeController::class, 'adminvieworder'])->name('admin.view.orders');
    // In routes/web.php
    Route::delete('/admin/users/{id}', [HomeController::class, 'destroy'])->name('admin.users.destroy');

});
Route::middleware(['auth', 'vendor'])->group(function () {
    //category routes
    Route::get('/category', [CategoryController::class, 'categoryview']);
    //Route::get('/category',[CategoryController::class,'categoryview']);
    Route::post('/storeCategory', [CategoryController::class, 'storeCategory'])->name('storeCategory');
    Route::post('/storeSubcategory', [CategoryController::class, 'storeSubcategory'])->name('storeSubcategory');
    // Delete a category and its subcategories
    Route::delete('/category/{category}', [CategoryController::class, 'destroycategory'])->name('deleteCategory');
    // Delete a subcategory individually
    Route::delete('/subcategory/{subcategory}', [CategoryController::class, 'destroySubcategory'])->name('deleteSubcategory');
    Route::put('/category/{category}', [CategoryController::class, 'updateCategory'])->name('updateCategory');
    Route::put('/subcategory/{subcategory}',[CategoryController::class, 'updateSubcategory'])->name('updateSubcategory');

    //products route
    Route::get('/productview', [ProductController::class, 'productview'])->name('productview');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::put('/product/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::get('/get-subcategories', [ProductController::class,'getSubcategories'])->name('getSubcategories');

    //orderdetailchangeroute
    Route::get('/orders',[OrderController::class,'index'])->name('order.index');
    Route::post('/change-order-details/{id}',[OrderController::class,'changeOrderDetails'])->name('change.order.details');
    Route::get('/orders/{id}',[OrderController::class,'orderdestroy'])->name('orders.destroy');
    //vendordashboard
    Route::get('/vendor-dashboard',[VendorController::class,'vendordashboardview'])->name('vendor.dashboard');
    //productreportdetail
    Route::get('/vendor-orderdetailreport',[VendorController::class,'showProductSalesDashboard'])->name('vendor.orderdetailreport');
    //graphviewroute
    Route::get('/vendor-dashboard',[VendorController::class,'salesDashboard']);

});
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::get('/get-subcategories', function () {
    $categoryId = request('category_id');
    $subcategories = Subcategory::where('category_id', $categoryId)->get();
    return response()->json($subcategories);
})->name('get-subcategories');
//cartroute
Route::get('/cart',[CartController::class,'Cartview'])->name('cartview');
Route::get('/product/{id}', [ProductController::class, 'productdetail'])->name('Detailproducts');
Route::get('/user/product/filter',[ProductController::class,'userProductFilter'])->name('user.product.filter');
//productview
Route::get('/userproduct',[ProductController::class,'userproductview'])->name('userproductview');
//checkoutview
Route::get('/checkout',[CheckoutController::class,'checkout'])->name('checkout');
//userorderdetails
Route::get('/userOrder',[OrderController::class,'userindex'])->name('userOrder');
//wishlist
Route::get('/wishlist',[WishlistController::class,'viewwishlist'])->name('wishlist');
//featuredproducts
Route::get('/', [ProductController::class, 'showFeaturedProducts']);


Route::middleware(['auth','user'])->group(function () {
    //cartroute
    Route::post('/cart/add/{product}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/update/{cartItem}', [CartController::class,'updateCartQuantity'])->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', [CartController::class,'removeFromCart'])->name('cart.remove');
    //checkoutroute
    Route::get('/update-order/{id}',[CheckoutController::class,'updateOrder']);
    Route::post('/place-order', [CheckoutController::class,'placeOrder']);
    Route::get('/pay-with-khalti/{price}/{order_id}', [CheckoutController::class,'payWithKhalti'])->name('pay.with.khalti');
    //khaltiordercancel
    Route::get('/cancel-order/{order_id}', [OrderController::class, 'cancelOrder'])->name('order.cancelled');
    //wishlist
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/remove/{wishlistItem}', [WishlistController::class,'removeFromWishlist'])->name('wishlist.remove');
    
});

Route::get('auth/google',[GoogleAuthController::class,'redirect'])->name('google-auth');
Route::get('auth/google/call-back',[GoogleAuthController::class,'callbackGoogle']);
Route::post('/request-vendor-role', [UserController::class, 'requestVendorRole'])->name('user.requestVendorRole');
Route::get('/vendordashboard', [UserController::class, 'showVendorRequestForm'])->name('vendordashboard');
