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
        try {
            // Delete the payment proof file if it exists
            if ($order->payment_proof && Storage::exists('public/payment_proofs/' . $order->payment_proof)) {
                Storage::delete('public/payment_proofs/' . $order->payment_proof);
            }
    
            // Update order status to cancelled
            $order->status = 'cancelled';
            $order->payment_proof = null;
            $order->save();
    
            Alert::success('Order Cancelled', 'The order has been cancelled and payment proof deleted.');
        } catch (\Exception $e) {
            \Log::error('Failed to cancel order: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order.'
            ], 500);
        }
    
        return redirect()->route('storemanager.orders.show', $order);
    }
    
    public function changeStatus(Request $request, Order $order)
    {
        try {
            $status = $request->input('status');
            \Log::info('Attempting to change order status', ['order_id' => $order->id, 'status' => $status]);
    
            $order->status = $status;
            $order->save();
    
            \Log::info('Order status changed successfully', ['order_id' => $order->id, 'status' => $status]);
    
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \Log::error('Failed to change order status: ' . $e->getMessage(), ['order_id' => $order->id, 'status' => $status]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to change order status.'
            ], 500);
        }
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

    public function generateInvoice($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        $pdf = Pdf::loadView('storemanager.orders.invoice', compact('order'));
        return $pdf->download('invoice-order-'.$order->id.'.pdf');
    }
}
