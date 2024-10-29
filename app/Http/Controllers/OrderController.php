<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\ProductSales;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // Get the logged-in vendor
        $vendor = Auth::user();
    
        // Ensure the user is a vendor
        if ($vendor->role != 'vendor') {
            abort(403, 'Unauthorized action.');
        }
    
        // Get orders associated with the vendor's products
        $orders = Order::whereHas('orderDetails', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })->get();
    
        if ($orders->isEmpty()) {
            return view('vendor/order/productorder', compact('orders'))
                ->with('message', 'No orders found for this vendor.');
        }
    
        foreach ($orders as $order) {
            $order_details = $order->orderDetails->where('vendor_id', $vendor->id);
    
            foreach ($order_details as $details) {
                $details['product_name'] = $details->product->product_name;
                $details['vendor_name'] = $vendor->name;
            }
            $order['order_details'] = $order_details;
        }
    
        return view('vendor/order/productorder', compact('orders'));
    }
    
    
function changeOrderDetails(Request $request, $id)
{
    $order = Order::find($id);
    $oldPaymentStatus = $order->payment_status;

    $order->payment_status = $request->payment_status;
    $order->order_status = $request->order_status;
    $order->shipping_status = $request->shipping_status;
    $order->save();

    // Check if payment status changed to 'paid'
        if ($oldPaymentStatus !== 'paid' && $order->payment_status === 'paid') {
            $this->addToProductSales($order->id);
        }

    return back()->with('message', 'Order Details Updated');
}
    function orderdestroy($id)
    {
            $order = Order::find($id);
            $order->delete();
    
            return back()->with('message', 'Order deleted successfully.');
        
    }
    function userindex(){
        // Get the logged-in user's ID
        $userId = Auth::id(); // Get the authenticated user's ID

        // Retrieve orders for the logged-in user only
        $orders = Order::where('user_id', $userId)->get();
        foreach($orders as $order)
        {
            $order_details = OrderDetails::where('order_id',$order->id)->get();
            foreach($order_details as $details)
            {
                $product = Product::find($details->product_id);
                $vendor = User::find($details->vendor_id);
                $details['product_name'] = $product->product_name;
                $details['vendor_name'] = $vendor->name;
            }
            $order['order_details'] = $order_details; 
        }

        return view('user/userOrder',compact('orders'));
    }
    public function cancelOrder($orderId)
    {
        // Retrieve the order from the database
        $order = Order::find($orderId);

        if ($order) {
            // Check if the order can be cancelled (e.g., not already shipped)
            if ($order->order_status === 'pending' || $order->order_status === 'processing') {
                // Revert stock
                $order_details = OrderDetails::where('order_id', $order->id)->get();
                foreach ($order_details as $details) {
                    $product = Product::find($details->product_id);
                    $product->stock += $details->quantity;
                    $product->save();
                }

                $order->order_status = 'cancelled';
                $order->save();

                return redirect()->route('order.cancelled', ['order_id' => $orderId]);
            } else {
                return redirect('/')->with('error', 'This order cannot be cancelled.');
            }
        } else {
            return redirect('/')->with('error', 'Order not found.');
        }
    }
    private function addToProductSales($orderId) 
    {
        $order = Order::find($orderId);
        $orderDetails = OrderDetails::where('order_id', $orderId)->get();

        foreach ($orderDetails as $detail) {
            $product = Product::find($detail->product_id);
            $vendor = User::find($detail->vendor_id);

            ProductSales::create([
                'user_name' => $order->customer_name,
                'product_name' => $product->product_name,
                'vendor_name' => $vendor->name,
                'quantity' => $detail->quantity,
                'price' => $detail->unit_price,
                'total_price' => $detail->subtotal,
                'commission' => $detail->commission,
            ]);
        }
    }
}
