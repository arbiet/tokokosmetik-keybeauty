<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class StoreManagerCategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = Category::query();

        if ($search) {
            $categories->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Mengambil jumlah produk per kategori
        $categories->withCount('products');

        $categories = $categories->paginate(8);

        return view('storemanager.categories.index', compact('categories'))->with('search', $search);
    }

    /**
     * Menampilkan formulir untuk membuat kategori baru.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('storemanager.categories.create');
    }

    /**
     * Menyimpan kategori baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi data yang dikirimkan oleh form
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Buat kategori baru berdasarkan data yang dikirimkan oleh form
        Category::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        // Redirect ke halaman daftar kategori dengan pesan sukses
        return redirect()->route('storemanager.categories.index')->with('success', 'Category added successfully!');
    }

    /**
     * Menampilkan formulir untuk mengedit kategori.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('storemanager.categories.edit', compact('category'));
    }

    /**
     * Menyimpan perubahan pada kategori yang diedit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|max:255',
            // tambahkan validasi lainnya sesuai kebutuhan
        ]);

        $category->update($request->all());

        return redirect()->route('storemanager.categories.index')
                         ->with('success', 'Category updated successfully.');
    }

    /**
     * Menghapus kategori.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // Hapus semua produk yang terkait dengan kategori yang akan dihapus
        $category->products()->delete();
    
        // Hapus kategori itu sendiri
        $category->delete();
    
        return redirect()->route('storemanager.categories.index')
                         ->with('success', 'Category and its related products deleted successfully.');
    }
    
}
