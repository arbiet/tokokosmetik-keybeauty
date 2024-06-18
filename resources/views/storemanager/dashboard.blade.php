<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-semibold mb-4">Sales Overview</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <p class="text-lg">Total Sales</p>
                            <p class="text-2xl font-bold">Rp. {{ number_format($totalSales, 2) }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg">
                            <p class="text-lg">Total Orders</p>
                            <p class="text-2xl font-bold">{{ $totalOrders }}</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-lg">
                            <p class="text-lg">Average Order Value</p>
                            <p class="text-2xl font-bold">Rp. {{ number_format($averageOrderValue, 2) }}</p>
                        </div>
                    </div>

                    <h3 class="text-2xl font-semibold mb-4">Top Products</h3>
                    <table class="min-w-full divide-y divide-gray-200 mb-6">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Sold</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($topProducts as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->total_quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp. {{ number_format($product->total_revenue, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h3 class="text-2xl font-semibold mb-4">Recent Orders</h3>
                    <table class="min-w-full divide-y divide-gray-200 mb-6">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->customer_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp. {{ number_format($order->total, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at->format('d-m-Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h3 class="text-2xl font-semibold mb-4">Inventory Status</h3>
                    @if ($lowStockProducts->isEmpty())
                        <p class="text-lg text-green-600">There are no products less than 10.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($lowStockProducts as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->stock }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
