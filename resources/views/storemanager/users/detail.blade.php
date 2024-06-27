<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('User Detail') }}
                </h2>
                <div class="text-gray-900">
                    {{ __("You're logged in as Store Manager!") }} 
                </div>
            </div>
            <div>
                <a href="{{ url()->previous() }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Back</a>
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
                <div class="bg-white">
                    <div class="flex flex-col md:flex-row mt-2">
                        <div class="w-full md:w-2/3 md:pl-4 mt-4 md:mt-0">
                            <div class="lg:col-span-2 lg:border-l lg:border-gray-200 lg:pl-6">
                                <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ $user->name }}</h1>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600"><strong>Email:</strong> {{ $user->email }}</p>
                                    <p class="text-sm text-gray-600"><strong>User Type:</strong> {{ $user->usertype }}</p>
                                    <p class="text-sm text-gray-600"><strong>Email Verified:</strong> {{ $user->email_verified_at ? 'Yes' : 'No' }}</p>
                                </div>
                                <div class="mt-4">
                                    <h3 class="text-sm font-medium text-gray-900">Actions</h3>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('storemanager.users.edit', ['user' => $user]) }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                            Edit
                                        </a>
                                        <form id="delete-form-{{ $user->id }}" action="{{ route('storemanager.users.destroy', ['user' => $user]) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $user->id }})" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                                Delete
                                            </button>
                                        </form>
                                        <form id="verify-form-{{ $user->id }}" action="{{ route('storemanager.users.verify', ['user' => $user]) }}" method="post">
                                            @csrf
                                            <button type="button" onclick="confirmVerify({{ $user->id }})" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                                Verify
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
