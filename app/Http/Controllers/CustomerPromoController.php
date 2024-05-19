<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;
use App\Models\Cart;

class CustomerPromoController extends Controller
{
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
