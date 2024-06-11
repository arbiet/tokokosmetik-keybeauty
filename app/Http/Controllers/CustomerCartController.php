<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Promo;
use Illuminate\Support\Facades\Auth;

class CustomerCartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = $user->cart()->with('product')->get();

        return view('customer.carts.index', compact('cart'));
    }

    public function invoice(Request $request)
    {
        $selectedCartIds = $request->query('cart_ids');
        $selectedCartIds = explode(',', $selectedCartIds);
        $cartItems = Cart::whereIn('id', $selectedCartIds)->with('product')->get();
        
        $subtotal = $cartItems->sum(function ($cartItem) {
            return $cartItem->product->price * $cartItem->quantity;
        });

        $discountAmount = 0;
        $totalAfterDiscount = $subtotal;

        $promoCode = $request->input('promo_code');
        $promo = Promo::where('promo_code', $promoCode)->first();

        if ($promo && $subtotal >= $promo->minimum_purchase) {
            $discountAmount = $promo->discount_amount;
            $totalAfterDiscount = $subtotal - $discountAmount;
        }

        return view('customer.carts.invoice', compact('cartItems', 'subtotal', 'discountAmount', 'totalAfterDiscount'));
    }
    
    public function addToCart(Request $request, $productId)
    {
        $user = Auth::user();
        $product = Product::findOrFail($productId);
        $quantity = $request->input('quantity');

        if ($quantity > $product->stock) {
            return response()->json(['error' => 'Quantity exceeds stock available'], 400);
        }

        $existingCartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingCartItem) {
            $existingCartItem->update([
                'quantity' => $existingCartItem->quantity + $quantity
            ]);
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
        }

        return response()->json(['success' => 'Product added to cart successfully.'], 200);
    }

    public function updateQuantity(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart->update([
            'quantity' => $request->quantity
        ]);

        return redirect()->route('customer.carts.index')->with('success', 'Quantity updated successfully.');
    }

    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $cart->delete();

        return redirect()->route('customer.carts.index')->with('success', 'Item removed from cart successfully.');
    }

    public function checkout(Request $request)
    {
        $selectedCartIds = $request->input('cart_ids');
        
        return view('customer.carts.invoice', ['cart_ids' => $selectedCartIds]);
    }

    private function getDiscount($promoCode)
    {
        return $promoCode === 'CODE10' ? 0.1 : ($promoCode === 'CODE20' ? 0.2 : 0);
    }
}