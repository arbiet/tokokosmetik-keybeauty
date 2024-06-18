<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;
use App\Models\Cart;
use Illuminate\Support\Facades\Log;

class CustomerPromoController extends Controller
{
    public function fetchPromos(Request $request)
    {
        Log::info('fetchPromos method called');
        try {
            $subtotal = $request->input('subtotal', 0); // Default to 0 if not provided
            Log::info('Subtotal: ' . $subtotal);
            
            // Check if subtotal is valid
            if (!is_numeric($subtotal)) {
                Log::error('Invalid subtotal: ' . $subtotal);
                return response()->json(['error' => 'Invalid subtotal'], 400);
            }

            // Fetch promos from database
            $promos = Promo::where('minimum_purchase', '<=', $subtotal)->get();
            Log::info('Promos fetched: ' . $promos->count());

            return response()->json($promos);
        } catch (\Exception $e) {
            // Log the exception message and stack trace for debugging
            Log::error('Error fetching promos: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Failed to fetch promo options'], 500);
        }
    }

    public function checkPromo(Request $request)
    {
        $promoCode = $request->input('promo_code');
        $selectedCartIds = $request->input('cart_ids');

        // Memeriksa apakah promo code ada dalam tabel promo
        $promo = Promo::where('promo_code', $promoCode)->first();

        if ($promo) {
            // Mengambil data keranjang belanja
            $cartItems = Cart::whereIn('id', $selectedCartIds)->with('product')->get();
            
            // Menghitung subtotal
            $subtotal = $cartItems->sum(function ($cartItem) {
                return $cartItem->product->price * $cartItem->quantity;
            });

            // Jika subtotal tidak memenuhi syarat minimum promo, kembalikan error
            if ($subtotal < $promo->minimum_purchase) {
                return redirect()->back()->withErrors(['error' => 'Minimum purchase requirement not met for this promo.'.$cartItems]);
            }

            // Hitung total diskon
            $discountAmount = $promo->discount_amount;
            $totalAfterDiscount = $subtotal - $discountAmount;

            // Kembalikan data promo dan total setelah diskon
            return redirect()->back()->with([
                'promo_code' => $promo->promo_code,
                'discount_amount' => $discountAmount,
                'total_after_discount' => $totalAfterDiscount
            ]);
        } else {
            // Jika promo code tidak valid, kembalikan error
            return redirect()->back()->withErrors(['error' => 'Invalid promo code.']);
        }
    }
}
