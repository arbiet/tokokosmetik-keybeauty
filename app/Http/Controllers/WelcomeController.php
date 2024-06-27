<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'like', '%' . $query . '%')
                            ->orderBy('created_at', 'desc')
                            ->paginate(8);

        return view('welcome', compact('products', 'query'));
    }
    
    public function show(Product $product)
    {
        return view('welcomeproductshow', compact('product'));
    }
}
