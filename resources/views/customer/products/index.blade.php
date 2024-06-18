<!-- resources\views\customer\products\index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product') }}
        </h2>
        <div class="text-gray-900">
            {{ __("You're logged in as Customer!") }}
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('customer.products.index') }}" method="GET" class="mb-4">
                    <div class="flex items-center">
                        <input type="text" name="query" value="{{ $query }}" placeholder="Search Products..." class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring focus:border-blue-300">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                @if($products->isEmpty())
                    <p class="text-center text-gray-600">No products found.</p>
                @else
                    <article class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($products as $product)
                            <div class="bg-gray-100 border border-gray-200 rounded-lg overflow-hidden">
                                <a href="{{ route('welcome.product.show', ['product' => $product]) }}">
                                    <img class="w-full h-64 object-cover object-center" src="{{ $product->image ? asset('storage/images/products/' . $product->image) : asset('storage/images/products/default.jpg') }}" alt="Product Image">
                                </a>
                                <div class="p-4">
                                    <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                                    <p class="text-gray-600">{{ $product->description }}</p>
                                    <p class="text-gray-600">Price: Rp.  {{ number_format($product->price, 2) }}</p>
                                    <p class="text-gray-600">Stock: {{ $product->stock }}</p>
                                    <p class="text-gray-600">Category: {{ $product->category->name }}</p>
                                    <div class="mt-4 flex justify-between items-center">
                                        <button onclick="showAddToCartModal({{ $product->id }}, '{{ $product->name }}', {{ $product->stock }})" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </article>
                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function showAddToCartModal(productId, productName, productStock) {
            Swal.fire({
                title: `Add ${productName} to Cart`,
                input: 'number',
                inputLabel: 'Quantity',
                inputAttributes: {
                    min: 1,
                    max: productStock,
                    step: 1
                },
                inputValue: 1,
                showCancelButton: true,
                confirmButtonText: 'Add to Cart',
                showLoaderOnConfirm: true,
                preConfirm: (quantity) => {
                    if (quantity < 1 || quantity > productStock) {
                        Swal.showValidationMessage(`Please enter a valid quantity (1-${productStock})`);
                    } else {
                        return fetch(`/customer/cart/add/${productId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ quantity: quantity })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText);
                            }
                            return response.json();
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error}`);
                        });
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Success!',
                        text: `${productName} has been added to your cart.`,
                        icon: 'success'
                    });
                }
            });
        }
    </script>
</x-app-layout>
