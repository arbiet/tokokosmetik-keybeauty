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
        
        // Menghitung subtotal
        $subtotal = $cartItems->sum(function ($cartItem) {
            return $cartItem->product->price * $cartItem->quantity;
        });

        // Inisialisasi discountAmount dan totalAfterDiscount
        $discountAmount = 0;
        $totalAfterDiscount = $subtotal;

        // Memeriksa apakah promo code disertakan dalam request
        $promoCode = $request->input('promo_code');

        // Memeriksa apakah promo code ada dalam tabel promo
        $promo = Promo::where('promo_code', $promoCode)->first();

        if ($promo) {
            // Jika promo code valid, periksa apakah total pembelian memenuhi syarat minimal
            if ($subtotal >= $promo->minimum_purchase) {
                // Jika memenuhi syarat minimal, terapkan diskon
                $discountAmount = $promo->discount_amount;
                $totalAfterDiscount = $subtotal - $discountAmount;
            }
        }

        return view('customer.carts.invoice', compact('cartItems', 'subtotal', 'discountAmount', 'totalAfterDiscount'));
    }
    
    public function addToCart($productId)
    {
        $user = Auth::user();
        $product = Product::findOrFail($productId);

        $existingCartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingCartItem) {
            $existingCartItem->update([
                'quantity' => $existingCartItem->quantity + 1
            ]);
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => 1
            ]);
        }

        return redirect()->route('customer.carts.index')->with('success', 'Product added to cart successfully.');
    }

    public function updateQuantity(Request $request, Cart $cart)
    {
        // Verifikasi otorisasi pengguna
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    
        // Validasi nilai quantity
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
    
        // Perbarui nilai quantity sesuai dengan nilai yang dikirimkan dari formulir
        $cart->update([
            'quantity' => $request->quantity
        ]);
    
        // Redirect kembali ke halaman keranjang dengan pesan sukses
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
        // Retrieve the selected cart items
        $selectedCartIds = $request->input('cart_ids');
        
        // Render the invoice checkout page with selected cart items
        return view('customer.carts.invoice', ['cart_ids' => $selectedCartIds]);
    }
    
    
    // public function checkout(Request $request)
    // {
    //     $user = Auth::user();
    //     $cartIds = $request->cart_ids;
        

    //     if ($cartIds) {
    //         // Buat objek Order
    //         $order = new Order();
    //         $order->user_id = $user->id;
    //         $order->total = 0; // Inisialisasi total
    //         $order->discount = 0; // Inisialisasi diskon
    //         $order->final_total = 0; // Inisialisasi total akhir
    //         $order->save();

    //         $selectedItems = Cart::whereIn('id', $cartIds)->where('user_id', $user->id)->get();
    //         $promoCode = $request->promo_code; // Ambil kode promo dari request

    //         // Implementasi logika checkout disini (contohnya mengurangi stok, membuat order, dll)
    //         foreach ($selectedItems as $cartItem) {
    //             // Hitung total awal
    //             $totalAmount = $cartItem->product->price * $cartItem->quantity;

    //             // Cek dan terapkan diskon berdasarkan kode promo jika ada
    //             if ($promoCode) {
    //                 $discount = $this->getDiscount($promoCode); // Dapatkan diskon berdasarkan kode promo
    //                 $totalAmount -= $totalAmount * $discount; // Terapkan diskon
    //             }

    //             // Buat order item dengan tanggal pembuatan hari ini
    //             $orderItem = OrderItem::create([
    //                 'order_id' => $order->id,
    //                 'product_id' => $cartItem->product_id,
    //                 'quantity' => $cartItem->quantity,
    //                 'price' => $cartItem->product->price,
    //             ]);

    //             // Update final total pada Order
    //             $order->total += $totalAmount; // Tambahkan total awal
    //             $order->discount += $totalAmount !== $cartItem->product->price * $cartItem->quantity ? $discount * 100 : 0; // Tambahkan diskon jika ada
    //         }

    //         // Hitung total akhir setelah semua item ditambahkan
    //         $order->final_total = $order->total - ($order->total * ($order->discount / 100));
    //         $order->save();

    //         // Hapus keranjang yang dipilih
    //         Cart::destroy($cartIds);

    //         return redirect()->route('customer.carts.index')->with('success', 'Checkout successful.');
    //     }

    //     return redirect()->route('customer.carts.index')->with('error', 'No items selected for checkout.');
    // }


    // Metode untuk mendapatkan diskon berdasarkan kode promo
    private function getDiscount($promoCode)
    {
        // Anda dapat memperluas logika ini sesuai dengan aturan diskon yang Anda inginkan
        return $promoCode === 'CODE10' ? 0.1 : ($promoCode === 'CODE20' ? 0.2 : 0);
    }

}
