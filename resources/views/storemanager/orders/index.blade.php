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
                                    <th class="p-2">Order Date</th>
                                    <th class="p-2">Status</th>
                                    <th class="p-2">Tracking Number</th>
                                    <th class="p-2">Shipping Service</th>
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
                                        <td class="p-2">Rp. {{ number_format($order->final_total, 2) }}</td>
                                        <td class="p-2">{{ $order->order_date }}</td>
                                        <td class="p-2">{{ ucfirst($order->status) }}</td>
                                        <td class="p-2">{{ $order->tracking_number ?? 'N/A' }}</td>
                                        <td class="p-2">{{ $order->shipping_service ?? 'N/A' }}</td>
                                        <td class="p-2 space-x-2 flex">
                                            @if($order->status == 'paid')
                                                <button onclick="checkPaymentProof({{ $order->id }}, '{{ Storage::url('public/payment_proofs/' . $order->payment_proof) }}')" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                                    <i class="fas fa-file-invoice-dollar"></i>
                                                </button>
                                                <button onclick="changeStatus({{ $order->id }}, 'cancelled')" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <button onclick="changeStatus({{ $order->id }}, 'packaging')" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @elseif($order->status == 'packaging')
                                                <button onclick="addTrackingNumber({{ $order->id }})" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                                    <i class="fas fa-truck"></i>
                                                </button>
                                            @elseif($order->status == 'shipped')
                                                <button onclick="completeOrder({{ $order->id }})" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                                @if($order->tracking_number && in_array($order->shipping_service, ['jne', 'pos', 'tiki']))
                                                    <button onclick="trackPackage('{{ $order->tracking_number }}', '{{ $order->shipping_service }}')" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                @endif
                                            @endif
                                            <a href="{{ route('storemanager.orders.show', $order) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                                <i class="fas fa-eye"></i>
                                            </a>
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
        function checkPaymentProof(orderId, paymentProofUrl) {
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
    
        function changeStatus(orderId, status) {
    Swal.fire({
        title: status === 'cancelled' ? 'Cancel Order' : 'Accept Order',
        text: `Are you sure you want to ${status === 'cancelled' ? 'cancel' : 'accept'} this order?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Yes, ${status === 'cancelled' ? 'cancel' : 'accept'} it!`
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/storemanager/orders/${orderId}/changeStatus`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status })
            }).then(response => {
                if (!response.ok) {
                    throw new Error(response.statusText)
                }
                return response.json()
            }).then(() => {
                Swal.fire('Success', `Order has been ${status === 'cancelled' ? 'cancelled' : 'accepted'}.`, 'success').then(() => {
                    location.reload();
                });
            }).catch(error => {
                Swal.fire('Error', `Failed to ${status === 'cancelled' ? 'cancel' : 'accept'} order.`, 'error');
            });
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
                            return response.json().then(error => { throw new Error(error.message) })
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
                    fetch(`/storemanager/orders/${orderId}/changeStatus`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ status: 'completed' })
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response.json()
                    }).then(() => {
                        Swal.fire('Success', 'Order has been marked as completed.', 'success').then(() => {
                            location.reload();
                        });
                    }).catch(error => {
                        Swal.fire('Error', 'Failed to mark order as completed.', 'error');
                    });
                }
            });
        }
    
        function trackPackage(trackingNumber, shippingService) {
            let url = '';
            
            switch (shippingService) {
                case 'jne':
                    url = `https://jne.co.id/tracking-package?cek-resi=${trackingNumber}`;
                    break;
                case 'pos':
                    url = `https://www.posindonesia.co.id/id/tracking?receiptId=${trackingNumber}`;
                    break;
                case 'tiki':
                    url = `https://tiki.id/id/track?tracking=${trackingNumber}`;
                    break;
                default:
                    return;
            }
    
            window.open(url, '_blank');
        }
    
        function cancelOrder(orderId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to cancel this order and delete the payment proof?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/storemanager/orders/${orderId}/cancel`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response.json()
                    }).then(() => {
                        Swal.fire('Cancelled', 'The order has been cancelled and payment proof deleted.', 'success').then(() => {
                            location.reload();
                        });
                    }).catch(error => {
                        Swal.fire('Error', `Failed to cancel order: ${error.message}`, 'error');
                    });
                }
            });
        }
    </script>
    
</x-app-layout>
