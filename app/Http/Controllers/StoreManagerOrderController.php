<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf;

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
    
        try {
            $order->tracking_number = $request->input('tracking_number');
            $order->status = 'shipped';
            $order->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Tracking number added and order status updated to shipped.'
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to add tracking number: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to add tracking number.'
            ], 500);
        }
    }

    public function completeOrder(Order $order)
    {
        $order->status = 'completed';
        $order->save();
        Alert::success('Order Completed', 'The order has been marked as completed.');

        return redirect()->route('storemanager.orders.show', $order);
    }

    public function changeStatus(Request $request, Order $order)
    {
        $status = $request->input('status');
        $order->status = $status;
        $order->save();

        return response()->json(['status' => 'success']);
    }

    public function generateInvoice($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        $pdf = Pdf::loadView('storemanager.orders.invoice', compact('order'));
        return $pdf->download('invoice-order-'.$order->id.'.pdf');
    }

}
