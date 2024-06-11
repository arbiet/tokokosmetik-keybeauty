<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class StoreManagerPromoController extends Controller
{
    public function index(Request $request)
    {
        $query = Promo::query();

        if ($request->has('search')) {
            $query->where('promo_code', 'like', '%' . $request->search . '%');
        }

        $promos = $query->paginate(10);
        return view('storemanager.promos.index', compact('promos'));
    }

    public function create()
    {
        return view('storemanager.promos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|unique:promos|max:255',
            'discount_amount' => 'required|numeric',
            'minimum_purchase' => 'required|numeric',
        ]);

        Promo::create($request->all());

        return redirect()->route('storemanager.promos.index')->with('success', 'Promo created successfully.');
    }

    public function edit(Promo $promo)
    {
        return view('storemanager.promos.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'promo_code' => 'required|max:255|unique:promos,promo_code,' . $promo->id,
            'discount_amount' => 'required|numeric',
            'minimum_purchase' => 'required|numeric',
        ]);

        $promo->update($request->all());

        return redirect()->route('storemanager.promos.index')->with('success', 'Promo updated successfully.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();

        return redirect()->route('storemanager.promos.index')->with('success', 'Promo deleted successfully.');
    }
}
