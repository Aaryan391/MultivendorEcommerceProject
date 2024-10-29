<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductSales;
use App\Models\User;
use App\Models\VendorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
    public function showVendorRequests()
    {
        $vendorRequests = VendorRequest::with('user')->where('status', 'pending')->get();
        return view('admin.vendor_requests', compact('vendorRequests'));
    }

    public function acceptVendorRequest($id)
    {
        $vendorRequest = VendorRequest::find($id);
        if ($vendorRequest) {
            $user = User::find($vendorRequest->user_id);
            $user->role = 'vendor';
            $user->save();

            $vendorRequest->status = 'accepted';
            $vendorRequest->save();
        }

        return redirect()->route('admin.vendorRequests')->with('success', 'Vendor request accepted.');
    }

    public function declineVendorRequest($id)
    {
        $vendorRequest = VendorRequest::find($id);
        if ($vendorRequest) {
            $vendorRequest->status = 'declined';
            $vendorRequest->save();
        }

        return redirect()->route('admin.vendorRequests')->with('error', 'Vendor request declined.');
    }
    public function userList(Request $request)
    {
        $query = User::query();

        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15);
        $roles = User::distinct('role')->pluck('role');
        return view('admin.user-list', compact('users', 'roles'));
    }
    public function showgraphdata(Request $request)
    {
        $totalAccounts = User::count();

        $startDate = $request->input('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $startDate = date('Y-m-d', strtotime($startDate));
        $endDate = date('Y-m-d', strtotime($endDate));

        $salesData = ProductSales::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_price) as total_sales'),
            DB::raw('SUM(commission) as total_commission')
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $productSales = ProductSales::select('product_name', DB::raw('SUM(quantity) as total_quantity'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('product_name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        $vendorSales = ProductSales::select('vendor_name', DB::raw('SUM(total_price) as total_sales'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('vendor_name')
            ->orderByDesc('total_sales')
            ->limit(10)
            ->get();

        return view('admin.graphviews', compact('salesData', 'productSales', 'vendorSales', 'startDate', 'endDate', 'totalAccounts'));
    }
    public function adminvieworder() {
        // Check if the user is an admin
        $admin = Auth::user();
        if ($admin->role != 'admin') {
            abort(403, 'Unauthorized action.');
        }
    
        // Get all orders for the admin to view
        $orders = Order::with('orderDetails.product')->get();
    
        if ($orders->isEmpty()) {
            return view('admin/adminorderview', compact('orders'))
                ->with('message', 'No orders found.');
        }
    
        foreach ($orders as $order) {
            // Adding product names and vendor names to order details
            $order_details = $order->orderDetails;
    
            foreach ($order_details as $details) {
                $details['product_name'] = $details->product->product_name;
                $details['vendor_name'] = $details->product->vendor->name; // Assuming product has a relationship with vendor
            }
    
            $order['order_details'] = $order_details;
        }
    
        return view('admin/adminorderview', compact('orders'));
    }
    public function destroy($id)
{
    // Find the user by ID
    $user = User::find($id);

    // Check if the user exists
    if (!$user) {
        return redirect()->route('admin.users')->with('error', 'User not found.');
    }

    // Delete the user
    $user->delete();

    return redirect()->route('admin.users')->with('message', 'User deleted successfully.');
}
    
}
