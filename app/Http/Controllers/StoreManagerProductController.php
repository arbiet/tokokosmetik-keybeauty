<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class StoreManagerProductController extends Controller
{
    /**
     * Menampilkan daftar produk.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
    
        $products = Product::query();
    
        if ($search) {
            $products->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        }
    
        // Tambahkan pengurutan berdasarkan created_at secara descending
        $products->latest();
    
        $products = $products->paginate(8);
    
        return view('storemanager.products.index', compact('products'));
    }

    /**
     * Menampilkan formulir untuk membuat produk baru.
     *
     * @return \Illuminate\Http\Response
     */
    public function createProduct()
    {
        // Ambil semua kategori
        $categories = Category::all();
    
        // Tampilkan formulir untuk membuat produk baru dan kirimkan data kategori
        return view('storemanager.products.create', compact('categories'));
    }
    
    /**
     * Menyimpan produk baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeProduct(Request $request)
    {
        // Validasi data yang dikirimkan oleh form
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // maksimum 2MB
            'category_id' => 'required|exists:categories,id', // tambahkan validasi untuk category_id
        ]);
        

        // Simpan gambar produk
        $imagePath = $request->file('image')->store('public/images/products');
        $slug = Str::slug($request->input('name'));
        // Gunakan while loop untuk memastikan slug yang unik
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        // Buat produk baru berdasarkan data yang dikirimkan oleh form
        $product = new Product([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
            'image' => basename($imagePath), // simpan nama file gambar saja
            'category_id' => $request->input('category_id'), // tambahkan category_id
            'slug' => $slug,
        ]);

        // Simpan produk ke dalam database
        $product->save();

        // Redirect ke halaman daftar produk dengan pesan sukses
        return redirect()->route('storemanager.products.index')->with('success', 'Product created successfully!');
    }



    // Metode untuk menampilkan detail produk
    public function detailProduct(Product $product)
    {
        return view('storemanager.products.detail', compact('product'));
    }

    // Metode untuk menampilkan formulir edit produk
    public function editProduct(Product $product)
    {
        // Ambil semua kategori
        $categories = Category::all();

        return view('storemanager.products.edit', compact('product','categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        // Validasi data yang dikirimkan oleh form
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // maksimum 2MB
            'category_id' => 'required|exists:categories,id', // tambahkan validasi untuk category_id
        ]);
    
        // Memperbarui atribut-atribut produk berdasarkan data yang dikirimkan oleh form
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->category_id = $request->input('category_id'); // tambahkan category_id
        $slug = Str::slug($request->input('name'));
        $product->slug = $slug;

    
        // Periksa apakah ada file gambar yang diunggah
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::delete('public/images/products/' . $product->image);
            }
    
            // Simpan gambar produk yang baru diunggah
            $imagePath = $request->file('image')->store('public/images/products');
            $product->image = basename($imagePath);
        }
    
        // Simpan perubahan pada produk
        $product->save();
    
        // Redirect kembali ke halaman detail produk dengan pesan sukses
        return redirect()->route('storemanager.products.detail', $product)->with('success', 'Product updated successfully!');
    }

    // Metode untuk menghapus produk
    public function destroyProduct(Product $product)
    {
        // Hapus produk dari database
        $product->delete();

        // Redirect kembali ke halaman daftar produk dengan pesan sukses
        return redirect()->route('storemanager.products.index')->with('success', 'Product deleted successfully!');
    }

    public function addStock(Request $request, Product $product)
    {
        // Validasi data yang dikirimkan oleh form
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Tambahkan stok produk sesuai dengan kuantitas yang ditentukan
        $product->stock += $request->input('quantity');
        $product->save();

        // Redirect kembali ke halaman detail produk dengan pesan sukses
        return redirect()->route('storemanager.products.detail', $product)->with('success', 'Stock added successfully!');
    }
}
