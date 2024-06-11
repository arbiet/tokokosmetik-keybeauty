<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class StoreManagerOrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(10);
        return view('storemanager.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('storemanager.orders.show', compact('order'));
    }

    public function verifyPayment(Request $request, Order $order)
    {
        if ($order->payment_proof && Storage::exists('public/payment_proofs/' . $order->payment_proof)) {
            $order->status = 'packaging';
            $order->save();
            Alert::success('Payment Verified', 'Order status has been updated to packaging.');
        } else {
            Alert::error('Verification Failed', 'No valid payment proof found.');
        }

        return redirect()->route('storemanager.orders.show', $order);
    }

    public function cancelOrder(Order $order)
    {
        $order->status = 'cancelled';
        $order->save();
        Alert::success('Order Cancelled', 'The order has been cancelled.');

        return redirect()->route('storemanager.orders.show', $order);
    }

    public function addTrackingNumber(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
        ]);

        $order->tracking_number = $request->input('tracking_number');
        $order->status = 'shipped';
        $order->save();
        Alert::success('Tracking Number Added', 'Order status has been updated to shipped.');

        return redirect()->route('storemanager.orders.show', $order);
    }

    public function completeOrder(Order $order)
    {
        $order->status = 'completed';
        $order->save();
        Alert::success('Order Completed', 'The order has been marked as completed.');

        return redirect()->route('storemanager.orders.show', $order);
    }
}
