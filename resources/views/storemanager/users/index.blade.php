<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Users') }}
                </h2>
                <div class="text-gray-900">
                    {{ __("You're logged in as Store Manager!") }} 
                </div>
            </div>
            <div class="flex">
                <div class="space-x-1 mr-2">
                    <button onclick="window.location.href='{{ route('storemanager.users.index') }}'" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">
                        <i class="fas fa-list"></i>
                    </button>
                    <button onclick="window.location.href='{{ route('storemanager.users.create') }}'" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <form action="{{ route('storemanager.users.index') }}" method="GET" class="mb-4">
                    <input type="text" name="search" placeholder="Search users..." value="{{ request()->input('search') }}" class="border border-gray-300 px-3 py-1 rounded-md focus:outline-none focus:border-indigo-500">
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

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
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
                                    <th class="p-2">Name</th>
                                    <th class="p-2">Email</th>
                                    <th class="p-2">User Type</th>
                                    <th class="p-2">Verified</th>
                                    <th class="p-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100">
                                @foreach($users as $user)
                                <tr>
                                    <td class="p-2">{{ $user->name }}</td>
                                    <td class="p-2">{{ $user->email }}</td>
                                    <td class="p-2">{{ $user->usertype }}</td>
                                    <td class="p-2">{{ $user->email_verified_at ? 'Yes' : 'No' }}</td>
                                    <td class="p-2 space-x-2 flex justify-start">
                                        <a href="{{ route('storemanager.users.detail', ['user' => $user]) }}" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!$user->email_verified_at)
                                        <form action="{{ route('storemanager.users.verify', ['user' => $user]) }}" method="post" id="verify-form-{{ $user->id }}">
                                            @csrf
                                            <button type="button" onclick="confirmVerify({{ $user->id }})" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <form id="delete-form-{{ $user->id }}" action="{{ route('storemanager.users.destroy', ['user' => $user]) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $user->id }})" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('storemanager.users.edit', ['user' => $user]) }}" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="m-6 mt-0">
                    {{ $users->appends(['search' => request()->input('search')])->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this user!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + userId).submit();
                }
            });
        }

        function confirmVerify(userId) {
            Swal.fire({
                title: 'Verify User',
                text: 'Are you sure you want to verify this user?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, verify it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('verify-form-' + userId).submit();
                }
            });
        }
    </script>
</x-app-layout>
