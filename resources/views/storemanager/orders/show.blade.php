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

                    <!-- Order Details Section -->
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
                                        <button onclick="viewPaymentProof({{ $order->id }}, '{{ Storage::url('public/payment_proofs/' . $order->payment_proof) }}')" class="text-green-500 hover:text-green-600">
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

                        <!-- Shipping Details Section -->
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

                        <!-- Tracking Information Section -->
                        <div class="mt-6">
                            <h4 class="font-semibold text-lg text-gray-900">
                                <i class="fas fa-truck"></i> Tracking Information
                            </h4>
                            <div class="mt-4">
                                <div class="flex justify-between">
                                    <div>
                                        <p class="font-semibold">Shipping Service:</p>
                                        <p>{{ $order->shipping_service }}</p>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Tracking Number:</p>
                                        <p>{{ $order->tracking_number }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items Section -->
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

                        <!-- Order Status Progress Section -->
                        <div class="mt-6 text-center">
                            <p class='text-2xl font-bold @if($order->status === 'unpaid') text-red-500 @elseif($order->status === 'paid') text-green-500 @elseif($order->status === 'packaging') text-yellow-500 @elseif($order->status === 'shipped') text-blue-500 @elseif($order->status === 'completed') text-green-700 @elseif($order->status === 'canceled') text-gray-500 @endif'>
                                {{ strtoupper($order->status) }}
                            </p>
                        </div>

                        <div class="mt-6">
                            <div class="flex justify-center items-center space-x-4">
                                <div class="text-center">
                                    <i class='fas fa-shopping-cart text-2xl @if($order->status !== 'unpaid') text-green-500 @else text-gray-500 @endif'></i>
                                    <p>Checkout</p>
                                </div>
                                <div class="text-center">
                                    <i class='fas fa-money-bill-wave text-2xl @if($order->status !== 'unpaid' && $order->status !== 'canceled') text-green-500 @else text-gray-500 @endif'></i>
                                    <p>Unpaid</p>
                                </div>
                                <div class="text-center">
                                    <i class='fas fa-check-circle text-2xl @if($order->status === 'paid' || $order->status === 'packaging' || $order->status === 'shipped' || $order->status === 'completed') text-green-500 @else text-gray-500 @endif'></i>
                                    <p>Paid</p>
                                </div>
                                <div class="text-center">
                                    <i class='fas fa-box text-2xl @if($order->status === 'packaging' || $order->status === 'shipped' || $order->status === 'completed') text-green-500 @else text-gray-500 @endif'></i>
                                    <p>Packaging</p>
                                </div>
                                <div class="text-center">
                                    <i class='fas fa-truck text-2xl @if($order->status === 'shipped' || $order->status === 'completed') text-green-500 @else text-gray-500 @endif'></i>
                                    <p>Shipped</p>
                                </div>
                                <div class="text-center">
                                    <i class='fas fa-check text-2xl @if($order->status === 'completed') text-green-500 @else text-gray-500 @endif'></i>
                                    <p>Completed</p>
                                </div>
                                @if($order->status === 'canceled')
                                <div class="text-center">
                                    <i class='fas fa-times-circle text-2xl @if($order->status === 'canceled') text-red-500 @else text-gray-500 @endif'></i>
                                    <p>Canceled</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function viewPaymentProof(orderId, paymentProofUrl) {
            let fileType = paymentProofUrl.split('.').pop();

            if (fileType === 'pdf') {
                Swal.fire({
                    title: 'Payment Proof',
                    html: `<embed src="${paymentProofUrl}" width="100%" height="400px" />`,
                    showCancelButton: true,
                    confirmButtonText: 'Accept Payment',
                    cancelButtonText: 'Cancel Order',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return fetch(`/storemanager/orders/${orderId}/changeStatus`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ status: 'packaging' })
                        }).then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText)
                            }
                            return response.json()
                        }).catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error}`)
                        })
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire('Success', 'Payment accepted and order status updated.', 'success').then(() => {
                            location.reload();
                        });
                    }
                });
            } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) {
                Swal.fire({
                    title: 'Payment Proof',
                    imageUrl: paymentProofUrl,
                    imageAlt: 'Payment Proof',
                    showCancelButton: true,
                    confirmButtonText: 'Accept Payment',
                    cancelButtonText: 'Cancel Order',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return fetch(`/storemanager/orders/${orderId}/changeStatus`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ status: 'packaging' })
                        }).then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText)
                            }
                            return response.json()
                        }).catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error}`)
                        })
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire('Success', 'Payment accepted and order status updated.', 'success').then(() => {
                            location.reload();
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Payment Proof',
                    text: 'Unsupported file format. Click below to download.',
                    showCancelButton: true,
                    confirmButtonText: 'Download File',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        window.location.href = paymentProofUrl;
                    }
                });
            }
        }
        function confirmComplete(orderId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to mark this order as completed?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, complete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form to complete the order
                    document.getElementById('complete-order-form-' + orderId).submit();
                }
            });
        }
    </script>

    @if ($order->status === 'shipped')
        <form id="complete-order-form-{{ $order->id }}" method="POST" action="{{ route('storemanager.orders.complete', $order->id) }}" style="display: none;">
            @csrf
        </form>
    @endif
</x-app-layout>
