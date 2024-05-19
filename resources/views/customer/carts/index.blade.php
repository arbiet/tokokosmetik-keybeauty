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
                                    <th class="p-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100 text-center">
                                @foreach ($cart as $cartItem)
                                    <tr>
                                        <td class="p-2">
                                            <input type="checkbox" name="cart_ids[]" value="{{ $cartItem->id }}">
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
                                        <td class="p-2">$ {{ number_format($cartItem->product->price, 2) }}</td>
                                        <td class="p-2">$ {{ number_format($cartItem->product->price * $cartItem->quantity, 2) }}</td>
                                        <td class="p-2">
                                            <span class="delete-item text-red-500 hover:text-red-700" data-id="{{ $cartItem->id }}">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button id="get-invoice" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Get Invoice
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Menambahkan event listener untuk tombol get invoice
            const getInvoiceButton = document.getElementById('get-invoice');
            if (getInvoiceButton) {
                getInvoiceButton.addEventListener('click', function() {
                    // Mengumpulkan cart_ids yang dipilih
                    const selectedCartIds = [];
                    const selectedCheckboxes = document.querySelectorAll('input[name="cart_ids[]"]:checked');
                    selectedCheckboxes.forEach(function(checkbox) {
                        selectedCartIds.push(checkbox.value);
                    });

                    // Buat URL dengan cart_ids sebagai parameter query
                    const queryString = selectedCartIds.length > 0 ? '?cart_ids=' + selectedCartIds.join(',') : '';
                    const url = '/customer/cart/checkout/invoice' + queryString;

                    // Arahkan pengguna ke halaman get invoice
                    window.location.href = url;
                });
            }


            const quantityControls = document.querySelectorAll('.quantity-control');
    
            // Menambahkan event listener untuk setiap tombol
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
    
                    // Mengubah nilai quantity di tampilan
                    quantityElement.innerText = quantity;
    
                    // Mengirim permintaan update quantity ke server
                    fetch(`/customer/cart/update/${productId}`, {
                        method: 'PATCH', // atau 'POST' jika menggunakan POST
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Tambahkan token CSRF
                        },
                        body: JSON.stringify({ quantity: quantity }) // Mengirim data quantity dalam format JSON
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log(data); // Response dari server, bisa di-handle sesuai kebutuhan
                    })
                    .catch(error => {
                        console.error('There was a problem with your fetch operation:', error);
                    });
                    location.reload();
                });
            });

            // Menambahkan event listener untuk tombol hapus item
            const deleteButtons = document.querySelectorAll('.delete-item');
            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-id');
                    
                    // Menampilkan SweetAlert2 konfirmasi
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
                            // Mengirim permintaan DELETE ke server
                            fetch(`/customer/carts/${productId}`, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Tambahkan token CSRF
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log(data); // Response dari server, bisa di-handle sesuai kebutuhan
                                // Hapus baris dari tampilan setelah item dihapus
                                const row = document.querySelector(`#product-name-${productId}`).parentNode;
                                row.parentNode.removeChild(row);
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
