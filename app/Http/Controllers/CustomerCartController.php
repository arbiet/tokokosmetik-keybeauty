<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Promo;
use App\Models\Address;
use App\Models\Province;
use App\Models\City;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CustomerCartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = $user->cart()->with('product')->get();
        $address = $user->addresses()->first();

        return view('customer.carts.index', compact('cart', 'address'));
    }

    public function calculateShippingCost(Request $request)
    {
        try {
            $origin = $request->input('origin');
            $destination = $request->input('destination');
            $weight = $request->input('weight');
            $courier = $request->input('courier');
    
            Log::info('Received parameters', [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier
            ]);
    
            if (empty($origin) || empty($destination) || empty($weight) || empty($courier)) {
                return response()->json(['error' => 'Missing required parameters'], 400);
            }
            
            $apiKey = 'f416a024129c0cea6c1351bf39ffb39d';
            if (empty($apiKey)) {
                return response()->json(['error' => 'API key is missing'], 500);
            }
            
            $response = Http::post('https://api.rajaongkir.com/starter/cost', [
                'key' => $apiKey,
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier,
            ]);
    
            $data = $response->json();
    
            if ($response->successful() && isset($data['rajaongkir']['results'])) {
                return response()->json($data['rajaongkir']['results']);
            } else {
                Log::error('Error fetching shipping cost', ['response' => $data]);
                return response()->json(['error' => 'Failed to fetch shipping cost'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Exception fetching shipping cost', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'Exception fetching shipping cost'], 500);
        }
    }

    public function invoice(Request $request)
    {
        $selectedCartIds = $request->query('cart_ids');
        $selectedCartIds = explode(',', $selectedCartIds);
        $cartItems = Cart::whereIn('id', $selectedCartIds)->with('product')->get();
        
        $subtotal = $cartItems->sum(function ($cartItem) {
            return $cartItem->product->price * $cartItem->quantity;
        });
        $totalweight = $cartItems->sum(function ($cartItem) {
            return $cartItem->product->weight * $cartItem->quantity / 1000;
        });
    
        $user = Auth::user();
        $address = $user->addresses()->first();
    
        // Origin location details
        $originLocation = 'Nganjuk (Kabupaten)';
        $originProvince = Province::where('province_name', 'Jawa Timur')->first();
        $originCity = City::where('city_name', 'Nganjuk (Kabupaten)')
                        ->where('province_id', $originProvince->province_id ?? null)
                        ->first();
        $originDetails = [
            'location' => $originLocation,
            'province_id' => $originProvince ? $originProvince->province_id : null,
            'city_id' => $originCity ? $originCity->city_id : null,
        ];
    
        // Destination location details
        $destinationLocation = $address ? $address->street . ', ' . $address->city . ', ' . $address->state . ', ' . $address->country . ', ' . $address->postal_code : 'Unknown Address';
        $destinationProvince = $address ? Province::where('province_name', $address->state)->first() : null;
        $destinationCity = $address ? City::where('city_name', $address->city)
                                    ->where('province_id', $destinationProvince->province_id ?? null)
                                    ->first() : null;
        $destinationDetails = [
            'location' => $destinationLocation,
            'province_id' => $destinationProvince ? $destinationProvince->province_id : null,
            'city_id' => $destinationCity ? $destinationCity->city_id : null,
        ];
    
        $locationDetails = [
            'origin' => $originDetails,
            'destination' => $destinationDetails,
        ];
    
        // Fetch shipping options
        $shippingOptions = $this->getShippingOptions($originDetails['city_id'], $destinationDetails['city_id'], $totalweight);
    
        // Fetch available promos
        $promos = Promo::where('minimum_purchase', '<=', $subtotal)
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->get();
    
        return view('customer.carts.invoice', compact(
            'cartItems', 
            'subtotal', 
            'totalweight', 
            'locationDetails',
            'shippingOptions',
            'promos'
        ));
    }

    private function getShippingOptions($origin, $destination, $weight)
    {
        $couriers = ['jne', 'pos', 'tiki'];
        $apiKey = 'f416a024129c0cea6c1351bf39ffb39d';
        $shippingOptions = [];

        foreach ($couriers as $courier) {
            $response = Http::post('https://api.rajaongkir.com/starter/cost', [
                'key' => $apiKey,
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight * 1000,
                'courier' => $courier,
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['rajaongkir']['results'])) {
                $shippingOptions = array_merge($shippingOptions, $data['rajaongkir']['results']);
            }
        }

        return $shippingOptions;
    }
    
    public function addToCart(Request $request, $productId)
    {
        $user = Auth::user();
        $product = Product::findOrFail($productId);
        $quantity = $request->input('quantity');

        if ($quantity > $product->stock) {
            return response()->json(['error' => 'Quantity exceeds stock available'], 400);
        }

        $existingCartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingCartItem) {
            $existingCartItem->update([
                'quantity' => $existingCartItem->quantity + $quantity
            ]);
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
        }

        return response()->json(['success' => 'Product added to cart successfully.'], 200);
    }

    public function updateQuantity(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart->update([
            'quantity' => $request->quantity
        ]);

        return redirect()->route('customer.carts.index')->with('success', 'Quantity updated successfully.');
    }

    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $cart->delete();

        return redirect()->route('customer.carts.index')->with('success', 'Item removed from cart successfully.');
    }

    public function checkout(Request $request)
    {
        $selectedCartIds = $request->input('cart_ids');
        
        return view('customer.carts.invoice', ['cart_ids' => $selectedCartIds]);
    }
}