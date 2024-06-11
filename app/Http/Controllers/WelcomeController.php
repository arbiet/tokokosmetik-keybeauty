<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('title', 'like', '%'.$query.'%')
                            ->orderBy('created_at', 'desc') // Mengurutkan produk berdasarkan waktu pembuatan
                            ->paginate(8); // Menambahkan pagination dengan 8 produk per halaman
        return view('welcome', compact('products', 'query'));
    }
    
    public function show(Product $product)
    {
        return view('welcomeproductshow', compact('product'));
    }
}
