<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }}
        </h2>
        <div class="text-gray-900">
            {{ __("You're logged in as Customer!") }}
        </div>
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
                            <a href="{{ route('customer.orders.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                <i class="fas fa-arrow-left"></i> Back to Orders
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

                        <!-- Action Buttons -->
                        <div class="mt-6 text-center">
                            <a href="{{ route('customer.orders.invoice', $order->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                <i class="fas fa-file-pdf"></i> Download Invoice
                            </a>
                        </div>
                    
                        @if ($order->status === 'shipped')
                            <div class="mt-6 text-center">
                                <button onclick="confirmComplete({{ $order->id }})" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                    <i class="fas fa-check"></i> Mark as Completed
                                </button>
                            </div>
                            <div class="mt-6 text-center">
                                <a href="https://wa.me/6285730221383?text=I%20have%20an%20issue%20with%20Order%20%23{{ $order->id }}" target="_blank" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                    <i class="fas fa-exclamation-triangle"></i> Report Issue via WhatsApp
                                </a>
                            </div>
                        @endif

                        @if ($order->status === 'unpaid' || $order->status === 'canceled')
                            <div class="mt-6">
                                <h4 class="font-semibold text-lg text-gray-900">
                                    <i class="fas fa-upload"></i> Upload Payment Proof
                                </h4>
                                <form method="POST" action="{{ route('customer.orders.uploadPaymentProof', $order->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mt-4">
                                        <input type="file" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" required>
                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                            <i class="fas fa-paper-plane"></i> Upload
                                        </button>
                                    </div>
                                </form>
                                @if ($errors->any())
                                    <div class="text-red-500 mt-4">
                                        {{ $errors->first() }}
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6">
                                <h4 class="font-semibold text-lg text-gray-900">
                                    <i class="fas fa-info-circle"></i> Payment Instructions
                                </h4>
                                <p class="mt-4">Please transfer the total amount to one of the following accounts:</p>
                                <ul class="mt-2">
                                    @foreach ($paymentMethods as $method)
                                        <li><i class="fas fa-wallet"></i> {{ $method }}</li>
                                    @endforeach
                                </ul>
                                <p class="mt-4 font-bold">Note: Use your order code <strong>Order #{{ $order->id }}</strong> as the payment reference.</p>
                            </div>
                        @endif

                        @if ($order->status === 'paid')
                            <div class="mt-6 text-center">
                                <a href="https://wa.me/6285730221383?text=I%20have%20paid%20for%20Order%20%23{{ $order->id }}" target="_blank" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                    <i class="fas fa-comment-dots"></i> Confirm via WhatsApp
                                </a>
                            </div>
                        @endif

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
                    showCancelButton: false,
                    confirmButtonText: 'Close',
                });
            } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) {
                Swal.fire({
                    title: 'Payment Proof',
                    imageUrl: paymentProofUrl,
                    imageAlt: 'Payment Proof',
                    showCancelButton: false,
                    confirmButtonText: 'Close',
                });
            } else {
                Swal.fire({
                    title: 'Payment Proof',
                    text: 'Unsupported file format. Click below to download.',
                    showCancelButton: false,
                    confirmButtonText: 'Download File',
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
        <form id="complete-order-form-{{ $order->id }}" method="POST" action="{{ route('customer.orders.complete', $order->id) }}" style="display: none;">
            @csrf
        </form>
    @endif
</x-app-layout>
