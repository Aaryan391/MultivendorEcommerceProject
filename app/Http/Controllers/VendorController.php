<?php

namespace App\Http\Controllers;

use App\Models\ProductSales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function vendordashboardview(){
        return view('vendor.navvendor');
    }
    public function showProductSalesDashboard(Request $request)
    {
        $user = Auth::user();
    
        if ($user) {
            $productSales = ProductSales::where('vendor_name', $user->name)
                            ->paginate(20); // 20 items per page
        
            return view('vendor.productsales.productreportdetail', compact('productSales'));
        }
    }
    public function salesDashboard(Request $request)
{
    // Get the logged-in vendor's ID and ensure the user is a vendor
    $vendorId = Auth::id();
    $vendorRole = Auth::user()->role;

    // Check if the logged-in user is a vendor
    if ($vendorRole !== 'vendor') {
        return redirect('/dashboard')->with('error', 'Unauthorized access.');
    }

    // Get date range from the request, defaulting to the last 30 days
    $startDate = $request->input('start_date', now()->subDays(30)->toDateString());
    $endDate = $request->input('end_date', now()->endOfDay());

    // Fetch sales data for the logged-in vendor
    $salesData = ProductSales::select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('SUM(total_price) as total_sales'),
        DB::raw('SUM(commission) as total_commission')
    )
    ->where('vendor_name', Auth::user()->name)  // Filter by the vendor's name
    ->whereBetween('created_at', [$startDate, $endDate])
    ->groupBy('date')
    ->orderBy('date')
    ->get();

    // Fetch top 5 product sales for the logged-in vendor
    $productSales = ProductSales::select('product_name', DB::raw('SUM(quantity) as total_quantity'))
        ->where('vendor_name', Auth::user()->name)  // Filter by the vendor's name
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('product_name')
        ->orderByDesc('total_quantity')
        ->limit(5)
        ->get();

    // Fetch top 5 vendor sales for the logged-in vendor
    $vendorSales = ProductSales::select('vendor_name', DB::raw('SUM(total_price) as total_sales'))
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('vendor_name')
        ->orderByDesc('total_sales')
        ->limit(5)
        ->get();

    // Pass the filtered data to the view
    return view('vendor.graphview.sales-dashboard', compact('salesData', 'productSales', 'vendorSales', 'startDate', 'endDate'));
}

}
