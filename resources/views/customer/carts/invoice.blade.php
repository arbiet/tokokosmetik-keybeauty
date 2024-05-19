<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
        <div class="text-gray-900">
            {{ __("You're logged in as Customer!") }} 
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div>
                        <h3 class="text-base font-semibold leading-7 text-gray-900">Invoice Checkout</h3>
                        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Checkout details</p>
                    </div>
                    <div class="mt-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($cartItems as $cartItem)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $cartItem->product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $cartItem->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">$ {{ $cartItem->product->price }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">$ {{ number_format($cartItem->product->price * $cartItem->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-right font-medium">Subtotal</td>
                                    <td class="px-6 py-4 whitespace-nowrap">$ {{ $subtotal }}</td>
                                </tr>
                                @if (session('discount_amount'))
                                <tr>
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-right font-medium">Promo Discount</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${{ session('discount_amount') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-right font-medium">Total After Discount</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${{ session('total_after_discount') }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6 flex justify-between">
                        <div>
                            <form action="{{ route('customer.promo.check') }}" method="POST">
                                @csrf
                                @foreach ($cartItems as $cartId)
                                    <input type="hidden" name="cart_ids[]" value="{{ $cartId->id }}">
                                @endforeach
                                <div>
                                    <input type="text" name="promo_code" class="border border-gray-300 rounded-md px-4 py-2" placeholder="Enter Promo Code">
                                    <button type="submit" class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Check Promo</button>
                                </div>
                            </form>
                        </div>
                        @if ($errors->any())
                            <div class="text-red-500">
                                {{ $errors->first() }}
                            </div>
                        @endif
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
