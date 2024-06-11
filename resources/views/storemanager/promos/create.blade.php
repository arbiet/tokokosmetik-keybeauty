<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Add Promo') }}
                </h2>
                <div class="text-gray-900">
                    {{ __("You're logged in as Store Manager!") }} 
                </div>
            </div>
            <a href="{{ url()->previous() }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Back</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('storemanager.promos.store') }}">
                        @csrf
                        <!-- Promo Code -->
                        <div class="mt-4">
                            <label for="promo_code" class="block text-sm font-medium leading-5 text-gray-700">Promo Code</label>
                            <input id="promo_code" type="text" name="promo_code" value="{{ old('promo_code') }}" required autofocus
                                class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md @error('promo_code') border-red-500 @enderror">
                            @error('promo_code')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Discount Amount -->
                        <div class="mt-4">
                            <label for="discount_amount" class="block text-sm font-medium leading-5 text-gray-700">Discount Amount</label>
                            <input id="discount_amount" type="number" step="0.01" name="discount_amount" value="{{ old('discount_amount') }}" required
                                class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md @error('discount_amount') border-red-500 @enderror">
                            @error('discount_amount')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Minimum Purchase -->
                        <div class="mt-4">
                            <label for="minimum_purchase" class="block text-sm font-medium leading-5 text-gray-700">Minimum Purchase</label>
                            <input id="minimum_purchase" type="number" step="0.01" name="minimum_purchase" value="{{ old('minimum_purchase') }}" required
                                class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md @error('minimum_purchase') border-red-500 @enderror">
                            @error('minimum_purchase')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit button -->
                        <div class="mt-6">
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150">
                                Add Promo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
