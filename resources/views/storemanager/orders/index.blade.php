<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
                            <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                                <tr>
                                    <th class="p-2">Order ID</th>
                                    <th class="p-2">Customer</th>
                                    <th class="p-2">Total Amount</th>
                                    <th class="p-2">Status</th>
                                    <th class="p-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100">
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="p-2">{{ $order->id }}</td>
                                        <td class="p-2">
                                            <a href="{{ route('storemanager.orders.show', $order) }}" class="text-blue-600 hover:underline">
                                                {{ $order->user->name }}
                                            </a>
                                        </td>
                                        <td class="p-2">{{ $order->total_amount }}</td>
                                        <td class="p-2">{{ $order->status }}</td>
                                        <td class="p-2 space-x-2 flex">
                                            @if($order->status == 'unpaid' && $order->payment_proof)
                                                <button onclick="verifyPayment({{ $order->id }})" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Verify Payment</button>
                                                <button onclick="cancelOrder({{ $order->id }})" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Cancel Order</button>
                                            @elseif($order->status == 'packaging')
                                                <button onclick="addTrackingNumber({{ $order->id }})" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Add Tracking Number</button>
                                            @elseif($order->status == 'shipped')
                                                <button onclick="completeOrder({{ $order->id }})" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Mark as Completed</button>
                                            @endif
                                            <a href="{{ route('storemanager.orders.show', $order) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="m-6 mt-0">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function verifyPayment(orderId) {
            Swal.fire({
                title: 'Verify Payment',
                text: 'Are you sure you want to verify this payment?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, verify it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/storemanager/orders/${orderId}/verifyPayment`;
                }
            });
        }

        function cancelOrder(orderId) {
            Swal.fire({
                title: 'Cancel Order',
                text: 'Are you sure you want to cancel this order?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = window.location.href = `/storemanager/orders/${orderId}/cancel`;
                }
            });
        }

        function addTrackingNumber(orderId) {
            Swal.fire({
                title: 'Add Tracking Number',
                input: 'text',
                inputLabel: 'Tracking Number',
                inputPlaceholder: 'Enter tracking number',
                showCancelButton: true,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                preConfirm: (tracking_number) => {
                    return fetch(`/storemanager/orders/${orderId}/addTrackingNumber`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ tracking_number })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response.json()
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`)
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Success',
                        text: 'Tracking number added successfully.',
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        function completeOrder(orderId) {
            Swal.fire({
                title: 'Complete Order',
                text: 'Are you sure you want to mark this order as completed?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, complete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/storemanager/orders/${orderId}/complete`;
                }
            });
        }
    </script>
</x-app-layout>

