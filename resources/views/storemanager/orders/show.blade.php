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
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Order ID: {{ $order->id }}</h3>
                        <p class="text-gray-600">Customer: {{ $order->user->name }}</p>
                        <p class="text-gray-600">Total Amount: {{ $order->total_amount }}</p>
                        <p class="text-gray-600">Status: {{ $order->status }}</p>
                    </div>

                    @if($order->status == 'unpaid' && $order->payment_proof)
                        <div class="mt-4">
                            <h4 class="text-md font-medium text-gray-900">Payment Proof</h4>
                            <img src="{{ asset('storage/payment_proofs/' . $order->payment_proof) }}" alt="Payment Proof" class="w-32 h-32 object-cover">
                        </div>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('storemanager.orders.index') }}" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">Back to Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
