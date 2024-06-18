<!-- resources\views\customer\carts\index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Carts') }}
        </h2>
        <div class="text-gray-900">
            {{ __("You're logged in as Customer!") }} 
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($cart->isEmpty())
                        <p class="text-gray-900">Your cart is empty.</p>
                    @else
                        <table class="table-auto w-full">
                            <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                                <tr>
                                    <th class="p-2">Select</th>
                                    <th class="p-2">Image</th>
                                    <th class="p-2">Product</th>
                                    <th class="p-2">Quantity</th>
                                    <th class="p-2">Price</th>
                                    <th class="p-2">Total</th>
                                    <th class="p-2">Weight</th>
                                    <th class="p-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100 text-center">
                                @foreach ($cart as $cartItem)
                                    <tr>
                                        <td class="p-2">
                                            <input type="checkbox" name="cart_ids[]" value="{{ $cartItem->id }}" data-price="{{ $cartItem->product->price * $cartItem->quantity }}" data-weight="{{ ($cartItem->product->weight * $cartItem->quantity) / 1000 }}">
                                        </td>
                                        <td class="p-2">
                                            <img src="{{ $cartItem->product->image ? asset('storage/images/products/' . $cartItem->product->image) : asset('storage/images/products/default.png') }}" alt="Product Image" class="w-8 h-8 object-cover">
                                        </td>
                                        <td class="p-2" id="product-name-{{ $cartItem->id }}">{{ $cartItem->product->name }}</td>
                                        <td class="p-2">
                                            <div class="flex items-center justify-center">
                                                <span class="quantity-control px-2 py-1 bg-gray-200 hover:bg-gray-300" data-id="{{ $cartItem->id }}" data-action="decrease">-</span>
                                                <span id="quantity-{{ $cartItem->id }}" class="px-2">{{ $cartItem->quantity }}</span>
                                                <span class="quantity-control px-2 py-1 bg-gray-200 hover:bg-gray-300" data-id="{{ $cartItem->id }}" data-action="increase">+</span>
                                            </div>
                                        </td>
                                        <td class="p-2">Rp. {{ number_format($cartItem->product->price, 2) }}</td>
                                        <td class="p-2">Rp. {{ number_format($cartItem->product->price * $cartItem->quantity, 2) }}</td>
                                        <td class="p-2">{{ ($cartItem->product->weight * $cartItem->quantity) / 1000 }} Kg</td>
                                        <td class="p-2">
                                            <span class="delete-item text-red-500 hover:text-red-700" data-id="{{ $cartItem->id }}">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5" class="p-2 text-right font-semibold">Total:</td>
                                    <td class="p-2" id="total-price-cell">Rp. 0.00</td>
                                    <td class="p-2" id="total-weight-cell">0.00 Kg</td>
                                    <td class="p-2"></td>
                                    <td class="p-2"></td>
                                </tr>
                            </tbody>
                        </table>
                        <button id="get-invoice" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
                            Checkout
                        </button>
                        
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="cart_ids[]"]');
            const totalPriceCell = document.getElementById('total-price-cell');
            const totalWeightCell = document.getElementById('total-weight-cell');
            const address = @json($address);
            
            function formatCurrency(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 2
                }).format(value).replace('IDR', 'Rp');
            }

            function updateTotals() {
                let totalPrice = 0;
                let totalWeight = 0;

                checkboxes.forEach(function(checkbox) {
                    if (checkbox.checked) {
                        totalPrice += parseFloat(checkbox.getAttribute('data-price'));
                        totalWeight += parseFloat(checkbox.getAttribute('data-weight'));
                    }
                });

                totalPriceCell.innerText = formatCurrency(totalPrice);
                totalWeightCell.innerText = `${totalWeight.toFixed(2).replace('.', ',')} Kg`;
            }

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateTotals);
            });

            // Call updateTotals initially in case some checkboxes are pre-checked
            updateTotals();

            const getInvoiceButton = document.getElementById('get-invoice');
            if (getInvoiceButton) {
                getInvoiceButton.addEventListener('click', function() {
                    if (!address) {
                        Swal.fire({
                            title: "Address Required",
                            text: "You need to fill in your address before proceeding to checkout.",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Fill Address",
                            cancelButtonText: "Cancel"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('profile.address') }}";
                            }
                        });
                        return;
                    }

                    const selectedCartIds = [];
                    const selectedCheckboxes = document.querySelectorAll('input[name="cart_ids[]"]:checked');
                    selectedCheckboxes.forEach(function(checkbox) {
                        selectedCartIds.push(checkbox.value);
                    });

                    const queryString = selectedCartIds.length > 0 ? '?cart_ids=' + selectedCartIds.join(',') : '';
                    const url = '/customer/cart/checkout/invoice' + queryString;

                    window.location.href = url;
                });
            }

            const quantityControls = document.querySelectorAll('.quantity-control');
            quantityControls.forEach(function(button) {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-id');
                    const action = this.getAttribute('data-action');
                    const quantityElement = document.getElementById('quantity-' + productId);
                    let quantity = parseInt(quantityElement.innerText);

                    if (action === 'decrease' && quantity > 1) {
                        quantity--;
                    } else if (action === 'increase') {
                        quantity++;
                    }

                    quantityElement.innerText = quantity;

                    fetch(`/customer/cart/update/${productId}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ quantity: quantity })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log(data);
                        location.reload();
                    })
                    .catch(error => {
                        console.error('There was a problem with your fetch operation:', error);
                    });
                });
            });

            const deleteButtons = document.querySelectorAll('.delete-item');
            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-id');
                    
                    Swal.fire({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this item!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Yes, delete it!",
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/customer/carts/${productId}`, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log(data);
                                const row = document.querySelector(`#product-name-${productId}`).parentNode;
                                row.parentNode.removeChild(row);
                                updateTotals();
                            })
                            .catch(error => {
                                console.error('There was a problem with your fetch operation:', error);
                            });
                            
                            Swal.fire("Deleted!", "Your item has been deleted.", "success");
                            location.reload();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
