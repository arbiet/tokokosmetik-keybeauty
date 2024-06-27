<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class StoreManagerProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
    
        $products = Product::query();
    
        if ($search) {
            $products->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        }
    
        $products->latest();
    
        $products = $products->paginate(8);
    
        return view('storemanager.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::all();
    
        return view('storemanager.products.create', compact('categories'));
    }
    
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
            'weight' => 'required|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'status' => 'required|string'
        ]);
    
        $imagePath = $request->file('image')->store('images/products', 'public');
        $slug = Str::slug($request->input('name'));
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
    
        $product = new Product([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
            'image' => basename($imagePath),
            'category_id' => $request->input('category_id'),
            'slug' => $slug,
            'weight' => $request->input('weight'),
            'length' => $request->input('length'),
            'width' => $request->input('width'),
            'height' => $request->input('height'),
            'status' => $request->input('status'),
        ]);
    
        $product->save();
    
        return redirect()->route('storemanager.products.index')->with('success', 'Product created successfully!');
    }

    public function detailProduct(Product $product)
    {
        return view('storemanager.products.detail', compact('product'));
    }

    public function editProduct(Product $product)
    {
        $categories = Category::all();

        return view('storemanager.products.edit', compact('product','categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
            'weight' => 'required|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'status' => 'required|string'
        ]);
    
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->category_id = $request->input('category_id');
        $slug = Str::slug($request->input('name'));
        $product->slug = $slug;
        $product->weight = $request->input('weight');
        $product->length = $request->input('length');
        $product->width = $request->input('width');
        $product->height = $request->input('height');
        $product->status = $request->input('status');
    
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete('images/products/' . $product->image);
            }
            $imagePath = $request->file('image')->store('images/products', 'public');
            $product->image = basename($imagePath);
        }
    
        $product->save();
    
        return redirect()->route('storemanager.products.detail', $product)->with('success', 'Product updated successfully!');
    }

    public function destroyProduct(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete('images/products/' . $product->image);
        }
        $product->delete();

        return redirect()->route('storemanager.products.index')->with('success', 'Product deleted successfully!');
    }

    public function addStock(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product->stock += $request->input('quantity');
        $product->save();

        return redirect()->route('storemanager.products.detail', $product)->with('success', 'Stock added successfully!');
    }
}
