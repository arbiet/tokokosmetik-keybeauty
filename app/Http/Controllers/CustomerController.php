<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Fetch recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->with('items.product')
            ->get();

        // Fetch order statistics
        $totalOrders = Order::where('user_id', $user->id)->count();
        $totalSpent = Order::where('user_id', $user->id)->sum('total');

        // Fetch recommended products (this is a simple example, you might want to implement a more sophisticated recommendation system)
        $recommendedProducts = Product::orderBy('popularity', 'desc')
            ->limit(5)
            ->get();

        return view('customer.dashboard', compact('recentOrders', 'totalOrders', 'totalSpent', 'recommendedProducts'));
    }
}
