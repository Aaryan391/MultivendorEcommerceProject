<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\ProductSales;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    function checkout()
    {
        return view('user/usercheckout');
    }
    public function placeOrder(Request $request)
{
    $order = new Order();
    $order->user_id = Auth::id();
    $order->order_payment_type = $request->order_payment_type;
    $order->order_total = 0;
    $order->order_status = 'pending';
    $order->payment_status = $request->order_payment_type == 'khalti' ? 'pending' : 'cod';
    $order->shipping_status = 'not_shipped';
    $order->customer_name = $request->customer_name;
    $order->customer_phone_number = $request->customer_phone_number;
    $order->customer_address = $request->customer_address;
    $order->customer_note = $request->customer_note;
    $order->customer_company = $request->customer_company;
    $order->customer_town_city = $request->customer_town_city;
    $order->save();

    $carts = Cart::where('user_id', Auth::user()->id)->get();

    foreach ($carts as $cart) {
        $product = Product::find($cart->product_id);

        if ($product->stock < $cart->quantity) {
            return redirect()->back()->with('error', 'Not enough stock for ' . $product->product_name);
        }

        $subtotal = $cart->quantity * $cart->unit_price;
        $commission_rate = 0.1; // 10% commission, adjust as needed
        $commission = $subtotal * $commission_rate;

        $order_details = new OrderDetails();
        $order_details->product_id = $cart->product_id;
        $order_details->vendor_id = $product->vendor_id;
        $order_details->quantity = $cart->quantity;
        $order_details->unit_price = $cart->unit_price;
        $order_details->subtotal = $subtotal;
        $order_details->commission = $commission;
        $order_details->order_id = $order->id;
        $order_details->save();

        // Decrease the product stock
        $product->stock -= $cart->quantity;
        $product->save();

        $order->order_total += $subtotal;
        $order->update();

        $cart->delete();
    }

    if ($request->order_payment_type == 'khalti') {
        $this->addToProductSales($order->id);
        return redirect()->route('pay.with.khalti', ['price' => $order->order_total, 'order_id' => $order->id]);
    } else {
        return redirect('/')->with('message', 'Your Order Has Been Placed');
    }
}

    function payWithKhalti($total_price, $order_id)
    {
        return view('user/paywithkhalti', compact('total_price', 'order_id'));
    }


    function updateOrder($id)
    {
        $order = Order::find($id);
        $oldStatus = $order->payment_status;
        $order->payment_status = 'paid';
        $order->update();
        if ($oldStatus == 'paid'&& $order->payment_status === 'paid') {
            $this->addToProductSales($order->id);
        }
        return redirect('/')->with('message', 'Your Order Has Been Placed Successfully');
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
