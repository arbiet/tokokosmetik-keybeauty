<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Categories') }}
                </h2>
                <div class="text-gray-900">
                    {{ __("You're logged in as Store Manager!") }} 
                </div>
            </div>
            <div class="flex items-center">
                <!-- Navigation Icons -->
                @if(request()->routeIs('storemanager.categories.index') || request()->routeIs('storemanager.categories.create'))
                    <div class="space-x-2 mr-2">
                        <button onclick="window.location.href='{{ route('storemanager.categories.index') }}'" class="bg-gray-500 text-white px-3 py-1 rounded-md hover:bg-gray-600">
                            <i class="fas fa-list"></i>
                        </button>
                        <button onclick="window.location.href='{{ route('storemanager.categories.create') }}'" class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                @endif
                <!-- Search Form -->
                <form action="{{ route('storemanager.categories.index') }}" method="GET" class="mr-4">
                    <input type="text" name="search" placeholder="Search categories..." class="border border-gray-300 px-3 py-1 rounded-md focus:outline-none focus:border-indigo-500">
                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600">
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <!-- Category Cards -->
                    @foreach($categories as $category)
                    <div class="bg-gray-100 border border-gray-200 rounded-lg overflow-hidden">
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2">{{ $category->name }}</h3>
                            <p class="text-gray-600">{{ $category->description }}</p>
                            <p class="text-gray-600">Product Count: {{ $category->products_count }}</p>
                            <div class="flex justify-between mt-4">
                                <a href="{{ route('storemanager.categories.edit', ['category' => $category]) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                <button onclick="confirmDeleteCategory('{{ $category->id }}')" class="text-red-500 hover:text-red-700">Delete</button>
                                <form id="delete-form-{{ $category->id }}" action="{{ route('storemanager.categories.destroy', ['category' => $category]) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="m-6 mt-4">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmDeleteCategory(categoryId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this category!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + categoryId).submit();
                }
            });
        }
    </script>
</x-app-layout>
