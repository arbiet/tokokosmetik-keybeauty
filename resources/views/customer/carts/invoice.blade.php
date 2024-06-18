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
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold text-gray-900">Choose Shipping Method</h4>
                        <div id="shipping-options" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($shippingOptions as $shippingOption)
                                @foreach ($shippingOption['costs'] as $cost)
                                    <div class="flex items-center justify-between py-2 px-4 border border-gray-300 rounded-md mb-2 shipping-option" onclick="selectShippingOption(this, {{ $cost['cost'][0]['value'] }})">
                                        <div>
                                            <p class="text-gray-900 font-semibold">{{ $shippingOption['name'] }} - {{ $cost['service'] }}</p>
                                            <p class="text-gray-500 text-sm">{{ $cost['description'] }}</p>
                                            <p class="text-gray-900 text-sm">ETD: {{ $cost['cost'][0]['etd'] }} days</p>
                                        </div>
                                        <p class="text-gray-900 font-bold">Rp. {{ number_format($cost['cost'][0]['value'], 2) }}</p>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold text-gray-900">Available Promos</h4>
                        <div id="promo-options" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($promos as $promo)
                                @if ($subtotal >= $promo->minimum_purchase)
                                    <div class="flex items-center justify-between py-2 px-4 border border-gray-300 rounded-md mb-2 promo-option" onclick="selectPromoOption(this, '{{ $promo->promo_code }}', {{ $promo->discount_percentage }}, {{ $promo->maximum_discount }})">
                                        <div>
                                            <p class="text-gray-900 font-semibold">Code: {{ $promo->promo_code }}</p>
                                            <p class="text-gray-500 text-sm">Discount: {{ $promo->discount_percentage }} %</p>
                                            <p class="text-gray-500 text-sm">Max Discount: Rp. {{ number_format($promo->maximum_discount, 2) }}</p>
                                            <p class="text-gray-500 text-sm">Min Purchase: Rp. {{ number_format($promo->minimum_purchase, 2) }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Invoice Checkout</h3>
                        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Checkout details</p>
                    </div>
                    <div class="mt-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($cartItems as $cartItem)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $cartItem->product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $cartItem->quantity }} pcs</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp. {{ number_format($cartItem->product->price, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ ($cartItem->product->weight * $cartItem->quantity) / 1000 }} Kg</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp. {{ number_format($cartItem->product->price * $cartItem->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-right font-bold">Checkout Detail</td>
                                    <td colspan="2" class="px-6 py-4 whitespace-nowrap font-bold">From {{ $locationDetails['origin']['location'] }} to {{ $locationDetails['destination']['location'] }} (From ID {{ $locationDetails['origin']['city_id'] }} to ID {{ $locationDetails['destination']['city_id'] }})</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-right font-medium">Total Weight</td>
                                    <td colspan="2" class="px-6 py-4 whitespace-nowrap font-bold">{{ $totalweight }} Kg</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-right font-medium">Subtotal</td>
                                    <td colspan="2" class="px-6 py-4 whitespace-nowrap font-bold">Rp. {{ number_format($subtotal, 2) }}</td>
                                </tr>
                                <tr id="promo-discount-row" style="display:none;">
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-right font-medium">Promo Discount</td>
                                    <td colspan="2" class="px-6 py-4 whitespace-nowrap" id="promo-discount">Rp. 0</td>
                                </tr>
                                <tr id="total-after-discount-row" style="display:none;">
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-right font-medium">Total After Discount</td>
                                    <td colspan="2" class="px-6 py-4 whitespace-nowrap" id="total-after-discount">Rp. 0</td>
                                </tr>
                                <tr id="shipping-details-row" style="display:none;">
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-right font-medium">Shipping Cost</td>
                                    <td colspan="2" class="px-6 py-4 whitespace-nowrap" id="shipping-cost">Rp. 0</td>
                                </tr>
                                <tr id="final-total-row" style="display:none;">
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-right font-bold">Final Total</td>
                                    <td colspan="2" class="px-6 py-4 whitespace-nowrap" id="final-total">Rp. 0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        <form method="POST" action="{{ route('customer.orders.store') }}">
                            @csrf
                            <input type="hidden" name="cart_ids" value="{{ implode(',', $cartItems->pluck('id')->toArray()) }}">
                            <input type="hidden" name="discount" id="hidden-discount" value="0">
                            <input type="hidden" name="shipping_cost" id="hidden-shipping-cost" value="0">
                            <input type="hidden" name="promo_code" id="hidden-promo-code" value="">
                            <input type="hidden" name="shipping_service" id="hidden-shipping-service" value="">

                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150">
                                Place Order
                            </button>
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
</x-app-layout>
<script>
    let selectedShippingOption = null;
    let selectedPromoOption = null;

    function selectShippingOption(element, cost) {
        if (selectedShippingOption) {
            selectedShippingOption.classList.remove('bg-blue-100', 'border-blue-500');
        }
        selectedShippingOption = element;
        selectedShippingOption.classList.add('bg-blue-100', 'border-blue-500');

        document.getElementById('shipping-cost').innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(cost);
        document.getElementById('shipping-details-row').style.display = 'table-row';
        document.getElementById('hidden-shipping-cost').value = cost;

        updateFinalTotal();
    }

    function selectPromoOption(element, promoCode, discountPercentage, maxDiscount) {
        if (selectedPromoOption) {
            selectedPromoOption.classList.remove('bg-blue-100', 'border-blue-500');
        }
        selectedPromoOption = element;
        selectedPromoOption.classList.add('bg-blue-100', 'border-blue-500');

        const subtotal = {{ $subtotal }};
        const discount = Math.min(subtotal * (discountPercentage / 100), maxDiscount);

        document.getElementById('promo-discount').innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(discount);
        document.getElementById('promo-discount-row').style.display = 'table-row';
        document.getElementById('hidden-discount').value = discount;
        document.getElementById('hidden-promo-code').value = promoCode;

        const totalAfterDiscount = subtotal - discount;
        document.getElementById('total-after-discount').innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(totalAfterDiscount);
        document.getElementById('total-after-discount-row').style.display = 'table-row';

        updateFinalTotal();
    }

    function updateFinalTotal() {
        const shippingCostText = document.getElementById('shipping-cost').innerText;
        const promoDiscountText = document.getElementById('promo-discount').innerText;

        const shippingCost = parseInt(shippingCostText.replace(/[^0-9]/g, '')) || 0;
        const promoDiscount = parseInt(promoDiscountText.replace(/[^0-9]/g, '')) || 0;

        const subtotal = {{ $subtotal }};
        const finalTotal = subtotal - promoDiscount + shippingCost;

        document.getElementById('final-total').innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(finalTotal);
        document.getElementById('final-total-row').style.display = 'table-row';
    }
</script>
