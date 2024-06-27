<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <div class="text-gray-900">
            {{ __("You're logged in as Customer!") }} 
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-semibold mb-4">Order Statistics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <p class="text-lg">Total Orders</p>
                            <p class="text-2xl font-bold">{{ $totalOrders }}</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-lg">
                            <p class="text-lg">Total Spend on Completed Orders</p>
                            <p class="text-2xl font-bold">Rp. {{ number_format($totalCompletedSpend, 2) }}</p>
                        </div>
                    </div>

                    <h3 class="text-2xl font-semibold mb-4">All Orders</h3>
                    <table class="min-w-full divide-y divide-gray-200 mb-6">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($allOrders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at->format('d-m-Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp. {{ number_format($order->total, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($order->status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h3 class="text-2xl font-semibold mb-4">Recent Orders</h3>
                    <table class="min-w-full divide-y divide-gray-200 mb-6">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at->format('d-m-Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp. {{ number_format($order->total, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($order->status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h3 class="text-2xl font-semibold mb-4">Recommended Products</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($recommendedProducts as $product)
                            <div class="bg-white p-4 rounded-lg shadow-md">
                                <h4 class="text-lg font-semibold">{{ $product->name }}</h4>
                                <p class="mt-2">Price: Rp. {{ number_format($product->price, 2) }}</p>
                                <p class="mt-2 text-gray-600">{{ Str::limit($product->description, 100) }}</p>
                                <a href="{{ route('welcome.product.show', ['product' => $product]) }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    View Product
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
