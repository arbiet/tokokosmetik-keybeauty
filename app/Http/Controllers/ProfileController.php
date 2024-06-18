<?php

// app\Http\Controllers\ProfileController.php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Province;
use App\Models\City;
use App\Models\Address;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function editAddress(Request $request): View
    {
        $user = $request->user();
        $address = $user->addresses()->first();

        // Initialize an empty address if none exists
        if (!$address) {
            $address = new Address();
        }

        $provinces = Province::all();

        // Find province and city IDs based on names and province
        $province = Province::where('province_name', $address->state)->first();
        $city = City::where('city_name', $address->city)
                    ->where('province_id', $province->province_id ?? null)
                    ->first();

        $address->province_id = $province ? $province->province_id : null;
        $address->city_id = $city ? $city->city_id : null;

        $cities = $province ? City::where('province_id', $province->province_id)->get() : collect([]);

        return view('profile.address', compact('address', 'provinces', 'cities'));
    }

    public function getCities($provinceId)
    {
        $cities = City::where('province_id', $provinceId)->get();
        return response()->json($cities);
    }

    public function getPostalCode($cityId)
    {
        $city = City::where('city_id', $cityId)->first();
        return response()->json($city ? $city->postal_code : null);
    }

    public function updateAddress(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'street' => 'required|string|max:255',
            'province' => 'required|integer',
            'city' => 'required|integer',
            'country' => 'required|string|max:255',
            'postal_code' => 'required|integer',
        ]);

        $user = Auth::user();

        \Log::info('User data: ', $validatedData); // Logging data

        $province = Province::find($request->province);
        $city = City::find($request->city);

        $addressData = [
            'street' => $validatedData['street'],
            'city' => $city->city_name,
            'state' => $province->province_name,
            'country' => $validatedData['country'],
            'postal_code' => $validatedData['postal_code']
        ];

        \Log::info('Address data to be saved: ', $addressData); // Logging address data

        $user->addresses()->updateOrCreate(
            ['user_id' => $user->id],
            $addressData
        );

        \Log::info('Address updated for user: ' . $user->id); // Logging after update

        return back()->with('status', 'address-updated');
    }

}
