<x-app-layout>
    <x-slot name="header">
        <!-- Header Navigation -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Products') }}
                </h2>
                <div class="text-gray-900">
                    {{ __("You're logged in as Store Manager!") }} 
                </div>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
    <script>
        // Display SweetAlert2 success message
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
        });
    </script>
    @endif

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Product Details -->
                <div class="bg-white">
                    <!-- Breadcrumb -->
                    <nav aria-label="Breadcrumb" class="flex justify-between items-center">
                        <ol role="list" class="flex items-center space-x-2">
                            <li>
                                <div class="flex items-center">
                                    <a href="#" class="mr-2 text-sm font-medium text-gray-900">{{ $product->category->name }}</a>
                                    <svg width="16" height="20" viewBox="0 0 16 20" fill="currentColor" aria-hidden="true" class="h-5 w-4 text-gray-300">
                                        <path d="M5.697 4.34L8.98 16.532h1.327L7.025 4.341H5.697z" />
                                    </svg>
                                </div>
                            </li>
                            <li class="text-sm">
                                <a href="#" aria-current="page" class="font-medium text-gray-500 hover:text-gray-600">{{ $product->name }}</a>
                            </li>
                        </ol>
                        <a href="{{ url()->previous() }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Back</a>
                    </nav>
                    <div class="flex flex-col md:flex-row mt-2">
                        <div class="w-full md:w-1/2"> <!-- Set width to half on desktop -->
                            <div class="aspect-h-4 aspect-w-3 overflow-hidden rounded-lg">
                                <img src="{{ $product->image ? Storage::url('images/products/' . $product->image) : Storage::url('images/products/default.png') }}" alt="Product Image" class="w-full h-full object-cover object-center">
                            </div>
                        </div>
                        <div class="w-full md:w-1/2 md:pl-4 mt-4 md:mt-0"> <!-- Set width to half on desktop -->
                            <div class="lg:col-span-2 lg:border-l lg:border-gray-200 lg:pl-6">
                                <!-- Product Title -->
                                <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ $product->name }}</h1>

                                <!-- Product Details -->
                                <div class="mt-4">
                                    <!-- Category, Price, Stock -->
                                    <p class="text-sm text-gray-600">Category: {{ $product->category->name }}</p>
                                    <p class="text-sm text-gray-600">Price: Rp. {{ number_format($product->price,2) }}</p>
                                    <p class="text-sm text-gray-600">Stock: {{ $product->stock }}</p>
                                </div>
                                <div class="mt-4">
                                    <!-- Description -->
                                    <h3 class="text-sm font-medium text-gray-900">Description</h3>
                                    <p class="text-base text-gray-900">{{ $product->description }}</p>
                                </div>
                            </div>
                            @if(Auth::user()->isCustomer())
                            <form class="mt-2">
                                <!-- Form to add product to cart -->
                                <button type="submit" class="flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Add to Cart</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
