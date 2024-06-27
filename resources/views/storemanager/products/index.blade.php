resources\views\storemanager\products\index.blade.php
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Products') }}
                </h2>
                <div class="text-gray-900">
                    {{ __("You're logged in as Store Manager!") }} 
                </div>
            </div>
            <div class="flex">
                <div class="space-x-1 mr-2">
                    <button onclick="window.location.href='{{ route('storemanager.products.index') }}'" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">
                        <i class="fas fa-list"></i>
                    </button>
                    <button onclick="window.location.href='{{ route('storemanager.products.create') }}'" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <form action="{{ route('storemanager.products.index') }}" method="GET" class="mb-4">
                    <input type="text" name="search" placeholder="Search products..." class="border border-gray-300 px-3 py-1 rounded-md focus:outline-none focus:border-indigo-500">
                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
        });
    </script>
    @endif
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
                            <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                                <tr>
                                    <th class="p-2">Image</th>
                                    <th class="p-2">Name</th>
                                    <th class="p-2">Description</th>
                                    <th class="p-2">Price</th>
                                    <th class="p-2">Stock</th>
                                    <th class="p-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100">
                                @foreach($products as $product)
                                <tr>
                                    <td class="p-2"><img src="{{ $product->image ? Storage::url('images/products/' . $product->image) : Storage::url('images/products/default.png') }}" alt="Product Image" class="w-8 h-8 object-cover"></td>
                                    <td class="p-2">{{ $product->name }}</td>
                                    <td class="p-2">{{ $product->description }}</td>
                                    <td class="p-2">Rp. {{ $product->price }}</td>
                                    <td class="p-2">{{ $product->stock }}</td>
                                    <td class="p-2 space-x-2 flex justify-start">
                                        <a href="{{ route('storemanager.products.edit', ['product' => $product->id]) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('storemanager.products.addStock', ['product' => $product]) }}" method="post" id="add-stock-form-{{ $product->id }}">
                                            @csrf
                                            <input type="number" name="quantity" id="quantity-{{ $product->id }}" value="1" min="1" class="hidden">
                                            <button type="button" onclick="confirmAddStock({{ $product->id }})" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                                <i class="fas fa-plus-circle"></i>
                                            </button>
                                        </form>
                                        <form id="delete-form-{{ $product->id }}" action="{{ route('storemanager.products.destroy', ['product' => $product]) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $product->id }})" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="m-6 mt-0">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmDelete(productId) {
            Swal.fire({
                title: 'Delete Product',
                text: 'Are you sure you want to delete this product?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + productId).submit();
                }
            });
        }
    
        function confirmAddStock(productId) {
            Swal.fire({
                title: 'Add Stock',
                html: '<input type="number" id="quantity" class="swal2-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" value="1" min="1">',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, add it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Get the value of the input field
                    let quantity = document.getElementById('quantity').value;

                    // Set the value of the hidden input field
                    document.getElementById('quantity-' + productId).value = quantity;

                    // Submit the form
                    document.getElementById('add-stock-form-' + productId).submit();
                }
            });
        }
    </script>
</x-app-layout>
