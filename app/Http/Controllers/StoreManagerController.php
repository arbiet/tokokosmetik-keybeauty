<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;

class StoreManagerController extends Controller
{
    public function index()
    {
        // Sales overview
        $totalSales = Order::sum('total');
        $totalOrders = Order::count();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Top products
        $topProducts = OrderItem::select('product_id', Product::raw('SUM(quantity) as total_quantity'), Product::raw('SUM(price * quantity) as total_revenue'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->with('product')
            ->get();

        // Recent orders
        $recentOrders = Order::orderBy('created_at', 'desc')->limit(5)->with('items.product')->get();

        // Inventory status
        $lowStockProducts = Product::where('stock', '<', 10)->get(); // Assuming 10 as low stock threshold

        return view('storemanager.dashboard', compact('totalSales', 'totalOrders', 'averageOrderValue', 'topProducts', 'recentOrders', 'lowStockProducts'));
    }
}
