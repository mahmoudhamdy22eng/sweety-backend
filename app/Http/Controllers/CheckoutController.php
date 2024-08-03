<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Address;
use App\Models\Shipping;
use App\Models\Payment;
use App\Models\AnalyticsLog;
use App\Models\Cart;

class CheckoutController extends Controller
{
    public function createOrder(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'shipping_method' => 'required|string', // New field for shipping method
            'shipping_cost' => 'required|numeric',  // New field for shipping cost
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|string',
        ]);

        // If shipping details are provided, create a new shipping record
        if (isset($validatedData['shipping_method']) && isset($validatedData['shipping_cost'])) {
            $shipping = Shipping::create([
                'method' => $validatedData['shipping_method'],
                'cost' => $validatedData['shipping_cost'],
            ]);
            $shippingId = $shipping->id;
        }

        // Get cart items for the authenticated user
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        // Calculate total order amount
        $total = $cartItems->reduce(function ($carry, $item) {
            return $carry + ($item->product->price * $item->quantity);
        }, 0);

        // Create a new order record
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'shipping_id' => $shippingId ?? $validatedData['shipping_id'] ?? null,
            'address_id' => $validatedData['address_id'],
        ]);

        // Record the payment associated with the order
        Payment::create([
            'order_id' => $order->id,
            'payment_method' => $validatedData['payment_method'],
            'status' => 'completed', // Assuming payment is successful
        ]);

        // Log the order completion for analytics
        AnalyticsLog::create([
            'action' => 'Completed Order',
            'action_type' => 'Order ID: ' . $order->id,
        ]);

        // Clear the user's cart
        Cart::where('user_id', Auth::id())->delete();

        // Return a successful response with the order ID
        return response()->json(['message' => 'Order placed successfully', 'order_id' => $order->id]);
    }

    public function getShippingInfo()
    {
        $address = Address::where('user_id', Auth::id())->first();

        return response()->json($address);
    }

    public function updateShippingInfo(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'country' => 'required|string',
        ]);

        $address = Address::updateOrCreate(
            ['user_id' => Auth::id()],
            $validatedData
        );

        return response()->json(['message' => 'Shipping info updated successfully']);
    }

    public function getDeliveryMethod()
    {
        $shipping = Shipping::first();

        return response()->json($shipping);
    }

    public function updateDeliveryMethod(Request $request)
    {
        $validatedData = $request->validate([
            'shipping_id' => 'nullable|exists:shippings,id',
        ]);

        // In a real application, you might update user's default or selected method
        // For now, we just acknowledge the change
        return response()->json(['message' => 'Delivery method updated successfully']);
    }

    public function getPaymentMethod()
    {
        // Return the default payment method
        return response()->json(['method' => 'Cash on Delivery']);
    }

    public function updatePaymentMethod(Request $request)
    {
        $validatedData = $request->validate([
            'method' => 'required|string',
        ]);

        // In a real application, you might store this in user's preferences
        // For now, we just acknowledge the change
        return response()->json(['message' => 'Payment method updated successfully']);
    }
}
