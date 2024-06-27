<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Orders') }}
        </h2>
        <div class="text-gray-900">
            {{ __("You're logged in as Customer!") }} 
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Order List</h3>
                    <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Here is a list of your orders.</p>
                    <div class="mt-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tracking Number</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipping Service</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $order->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $order->order_date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($order->status) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $order->tracking_number ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $order->shipping_service ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp. {{ number_format($order->final_total, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('customer.orders.show', $order->id) }}" class="text-blue-500 hover:text-blue-700">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('customer.dashboard.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Back to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
