<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->with('items.product')->get();
        return view('customer.orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $selectedCartIds = $request->input('cart_ids');
        $selectedCartIds = explode(',', $selectedCartIds);
        $cartItems = Cart::whereIn('id', $selectedCartIds)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->withErrors('No items in the cart.');
        }

        $subtotal = $cartItems->sum(function ($cartItem) {
            return $cartItem->product->price * $cartItem->quantity;
        });

        $discount = $request->input('discount', 0);
        $shippingCost = $request->input('shipping_cost', 0);
        $totalWeight = $cartItems->sum(function ($cartItem) {
            return $cartItem->product->weight * $cartItem->quantity / 1000;
        });
        $finalTotal = $subtotal - $discount + $shippingCost;

        $address = $user->addresses()->first();
        $destinationLocation = $address ? $address->street . ', ' . $address->city . ', ' . $address->state . ', ' . $address->country . ', ' . $address->postal_code : 'Unknown Address';

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'unpaid',
                'total' => $subtotal,
                'discount' => $discount,
                'final_total' => $finalTotal,
                'promo_code' => $request->input('promo_code', null),
                'shipping_service' => $request->input('shipping_service', null),
                'shipping_cost' => $shippingCost,
                'total_weight' => $totalWeight,
                'order_date' => now(),
                'origin_location' => 'Toko Kosmetik Keybeauty, Nganjuk',
                'destination_location' => $destinationLocation,
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);

                // Reduce product stock
                $product = Product::find($cartItem->product_id);
                if ($product) {
                    $product->stock -= $cartItem->quantity;
                    $product->save();
                }

                // Remove items from cart
                $cartItem->delete();
            }

            DB::commit();
            return redirect()->route('customer.orders.show', $order->id)->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Failed to create order.');
        }
    }

    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        $paymentMethods = [
            'Dana/ShopeePay: +62 857-3022-1383',
            'Bank BRI: 6423 0101 6152 534 ',
            'Atas Nama: Aprilia Salsa Bella'
        ];

        return view('customer.orders.show', compact('order', 'paymentMethods'));
    }

    public function uploadPaymentProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
    
        $order = Order::findOrFail($id);
    
        if ($request->hasFile('payment_proof')) {
            // Delete the old payment proof if exists
            if ($order->payment_proof) {
                Storage::delete('public/payment_proofs/' . $order->payment_proof);
            }
    
            // Store the new payment proof
            $fileName = $request->file('payment_proof')->hashName();
            $request->file('payment_proof')->storeAs('public/payment_proofs', $fileName);
    
            $order->update([
                'payment_proof' => $fileName,
                'status' => 'paid',
                'payment_date' => now(),
            ]);
    
            return redirect()->route('customer.orders.show', $order->id)->with('success', 'Payment proof uploaded successfully.');
        }
    
        return redirect()->back()->withErrors('Failed to upload payment proof.');
    }

    public function generateInvoice($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        $pdf = Pdf::loadView('customer.orders.invoice', compact('order'));
        return $pdf->download('invoice-order-' . $order->id . '.pdf');
    }

    public function complete($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status === 'shipped') {
            $order->update(['status' => 'completed']);
            return redirect()->route('customer.orders.show', $order->id)->with('success', 'Order marked as completed.');
        }

        return redirect()->back()->withErrors('Unable to mark order as completed.');
    }
}
