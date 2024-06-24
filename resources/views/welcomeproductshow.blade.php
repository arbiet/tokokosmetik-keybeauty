<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Include SweetAlert library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="min-h-screen flex flex-col justify-start">
    <!-- Navigation -->
    @include('layouts.navigation')
    <!-- Page Content -->
    <main class="flex justify-center items-center h-full">
        <form action="{{ route('welcome') }}" method="GET" class="w-full md:w-1/2 lg:w-1/3 px-4">
            <div class="flex items-center  py-2">
                <input type="text" name="query" value="" placeholder="Search Products..." class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring focus:border-blue-300">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </main>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- product Details -->
                <div class="bg-white">
                    <!-- Breadcrumb -->
                    <nav aria-label="Breadcrumb" class="flex justify-between items-center">
                        <ol role="list" class="flex items-center space-x-2">
                            <!-- You can change these links according to your application -->
                            <li>
                                <div class="flex items-center">
                                    <a href="#" class="mr-2 text-sm font-medium text-gray-900">{{ $product->category->name }}</a>
                                    <svg width="16" height="20" viewBox="0 0 16 20" fill="currentColor" aria-hidden="true" class="h-5 w-4 text-gray-300">
                                        <path d="M5.697 4.34L8.98 16.532h1.327L7.025 4.341H5.697z" />
                                    </svg>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <a href="#" class="mr-2 text-sm font-medium text-gray-900">{{ $product->name }}</a>
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
                        <div class="w-full md:w-1/3">
                            <div class="aspect-h-4 aspect-w-3 overflow-hidden rounded-lg">
                                <img src="{{ $product->image ? asset('storage/images/products/' . $product->image) : asset('storage/images/products/product.jpeg') }}" alt="product Cover" class="w-full h-full object-cover object-center">
                            </div>
                        </div>
                        <div class="w-full md:w-2/3 md:pl-4 mt-4 md:mt-0">
                            <div class="lg:col-span-2 lg:border-l lg:border-gray-200 lg:pl-6">
                                <!-- product Title -->
                                <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ $product->name }}</h1>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Description</h3>
                                    <p class="text-base text-gray-900">{{ $product->description }}</p>
                                </div>
                                <!-- Price, Rating, Stock -->
                                <div class="mt-4 space-y-2">
                                    <p class="text-sm text-gray-600">Price: Rp. {{ number_format($product->price,2) }}</p>
                                    <p class="text-sm text-gray-600">Rating: {{ $product->rating }}/5</p>
                                    <p class="text-sm text-gray-600">Stock: {{ $product->stock }}</p>
                                </div>
                            </div>
                            @auth
                                @if(Auth::user()->isCustomer())
                                    <div class="mt-4 flex justify-between items-center">
                                        <form action="{{ route('customer.carts.add', ['product' => $product->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add to Cart</button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
