<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CustomerProductController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'like', '%'.$query.'%')
                            ->orderBy('created_at', 'desc')
                            ->paginate(8);

        return view('customer.products.index', compact('products', 'query'));
    }
}
