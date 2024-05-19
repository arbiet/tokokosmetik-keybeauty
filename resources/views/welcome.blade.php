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
    <main class="flex justify-center items-center h-full mt-4">
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
                
            </div>
        </div>
    </div>
</div>
</body>
</html>
