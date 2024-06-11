<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Promos') }}
                </h2>
                <div class="text-gray-900">
                    {{ __("You're logged in as Store Manager!") }} 
                </div>
            </div>
            <div class="flex items-center">
                @if(request()->routeIs('storemanager.promos.index') || request()->routeIs('storemanager.promos.create'))
                    <div class="space-x-2 mr-2">
                        <button onclick="window.location.href='{{ route('storemanager.promos.index') }}'" class="bg-gray-500 text-white px-3 py-1 rounded-md hover:bg-gray-600">
                            <i class="fas fa-list"></i>
                        </button>
                        <button onclick="window.location.href='{{ route('storemanager.promos.create') }}'" class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                @endif
                <form action="{{ route('storemanager.promos.index') }}" method="GET" class="mr-4">
                    <input type="text" name="search" placeholder="Search promos..." value="{{ request('search') }}" class="border border-gray-300 px-3 py-1 rounded-md focus:outline-none focus:border-indigo-500">
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
                    @foreach($promos as $promo)
                    <div class="bg-gray-100 border border-gray-200 rounded-lg overflow-hidden">
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2">{{ $promo->promo_code }}</h3>
                            <p class="text-gray-600">Discount: {{ $promo->discount_amount }}</p>
                            <p class="text-gray-600">Minimum Purchase: {{ $promo->minimum_purchase }}</p>
                            <div class="flex justify-between mt-4">
                                <a href="{{ route('storemanager.promos.edit', ['promo' => $promo]) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                <button onclick="confirmDeletePromo('{{ $promo->id }}')" class="text-red-500 hover:text-red-700">Delete</button>
                                <form id="delete-form-{{ $promo->id }}" action="{{ route('storemanager.promos.destroy', ['promo' => $promo]) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="m-6 mt-4">
                    {{ $promos->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmDeletePromo(promoId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this promo!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + promoId).submit();
                }
            });
        }
    </script>
</x-app-layout>
