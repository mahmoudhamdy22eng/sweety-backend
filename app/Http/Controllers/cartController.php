<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $cartItem = Cart::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $validatedData['product_id']],
            ['quantity' => \DB::raw('quantity + 1')]
        );

        return response()->json(['message' => 'Product added to cart'], 200);
    }

    public function getCartItems()
    {
        $cartItems = Cart::with('product') // Eager load the product relationship
            ->where('user_id', Auth::id())
            ->get();

        // Format the cart items to include product details
        $cartItems = $cartItems->map(function ($cartItem) {
            return [
                'id' => $cartItem->id,
                'product_id' => $cartItem->product_id,
                'product_name' => $cartItem->product->name,
                'price' => $cartItem->product->price,
                'quantity' => $cartItem->quantity,
            ];
        });

        return response()->json($cartItems);
    }

    // public function updateQuantity(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'product_id' => 'required|exists:products,id',
    //         'quantity' => 'required|integer|min:1',
    //     ]);

    //     $cartItem = Cart::where('user_id', Auth::id())
    //         ->where('product_id', $validatedData['product_id'])
    //         ->first();

    //     if ($cartItem) {
    //         $cartItem->quantity = $validatedData['quantity'];
    //         $cartItem->save();
    //     }

    //     return response()->json(['message' => 'Quantity updated'], 200);
    // }
    public function updateQuantity(Request $request)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    // Find the cart item for the authenticated user and the specified product
    $cartItem = Cart::where('user_id', Auth::id())
        ->where('product_id', $validatedData['product_id'])
        ->first();

    // Check if the cart item exists
    if ($cartItem) {
        // Update the quantity
        $cartItem->quantity = $validatedData['quantity'];
        $cartItem->save(); // Save the changes to the database
    }

    // Fetch the updated cart items and calculate the total
    $cartItems = Cart::with('product')
        ->where('user_id', Auth::id())
        ->get();

    $total = $cartItems->reduce(function ($carry, $item) {
        return $carry + ($item->product->price * $item->quantity);
    }, 0);

    // Return the updated cart items and total in the response
    return response()->json(['cartItems' => $cartItems, 'total' => $total], 200);
}




    public function removeFromCart($productId)
    {
        Cart::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();

        return response()->json(['message' => 'Product removed from cart'], 200);
    }

    public function clearCart()
    {
        Cart::where('user_id', Auth::id())->delete();

        return response()->json(['message' => 'Cart cleared'], 200);
    }


    public function getCartTotal()
{
    $cartItems = Cart::with('product')
        ->where('user_id', Auth::id())
        ->get();

    $total = $cartItems->reduce(function ($carry, $item) {
        return $carry + ($item->product->price * $item->quantity);
    }, 0);

    return response()->json(['total' => $total]);
}


public function getCartItemsWithTotal()
{
    $cartItems = Cart::with('product')
        ->where('user_id', Auth::id())
        ->get();

    $total = $cartItems->reduce(function ($carry, $item) {
        return $carry + ($item->product->price * $item->quantity);
    }, 0);

    // Format the cart items to include product details
    $cartItems = $cartItems->map(function ($cartItem) {
        return [
            'id' => $cartItem->id,
            'product_id' => $cartItem->product_id,
            'product_name' => $cartItem->product->name,
            'price' => $cartItem->product->price,
            'quantity' => $cartItem->quantity,
        ];
    });

    return response()->json(['cartItems' => $cartItems, 'total' => $total]);
}


}

