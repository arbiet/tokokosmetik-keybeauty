<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-row justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-receipt"></i> Order #{{ $order->id }}
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Order Details</p>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('storemanager.orders.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                <i class="fas fa-arrow-left"></i> Back to Orders
                            </a>
                            <a href="{{ route('storemanager.orders.generateInvoice', $order->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 ml-2">
                                <i class="fas fa-file-pdf"></i> Generate Invoice
                            </a>
                        </div>
                    </div>
                    <div class="mt-6">
                        <div class="flex justify-between">
                            <div>
                                <p class="font-semibold">Order Date:</p>
                                <p>{{ $order->order_date }}</p>
                            </div>
                            <div>
                                <p class="font-semibold">Status:</p>
                                <p>
                                    {{ ucfirst($order->status) }}
                                    @if($order->payment_proof)
                                        <button onclick="viewPaymentProof('{{ asset('storage/' . $order->payment_proof) }}')" class="text-green-500 hover:text-green-600">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="font-semibold">Total:</p>
                                <p>Rp. {{ number_format($order->final_total, 2) }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h4 class="font-semibold text-lg text-gray-900">
                                <i class="fas fa-shipping-fast"></i> Shipping Details
                            </h4>
                            <div class="flex justify-between mt-4">
                                <div>
                                    <p class="font-semibold">Origin Location:</p>
                                    <p>{{ $order->origin_location }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold">Destination Location:</p>
                                    <p>{{ $order->destination_location }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold">Shipping Cost:</p>
                                    <p>Rp. {{ number_format($order->shipping_cost, 2) }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold">Total Weight:</p>
                                    <p>{{ $order->total_weight }} kg</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h4 class="font-semibold text-lg text-gray-900">
                                <i class="fas fa-box"></i> Order Items
                            </h4>
                            <table class="min-w-full divide-y divide-gray-200 mt-4">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->product->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">Rp. {{ number_format($item->price, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">Rp. {{ number_format($item->quantity * $item->price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function viewPaymentProof(url) {
            Swal.fire({
                title: 'Payment Proof',
                imageUrl: url,
                imageAlt: 'Payment Proof',
                showCloseButton: true,
                focusConfirm: false,
            });
        }
    </script>
</x-app-layout>
