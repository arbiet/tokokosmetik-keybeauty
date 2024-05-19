<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit Category') }}
                </h2>
                <div class="text-gray-900">
                    {{ __("You're logged in as Store Manager!") }} 
                </div>
            </div>
            <a href="{{ url()->previous() }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Back</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('storemanager.categories.update', $category->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium leading-5 text-gray-700">Name</label>
                            <input id="name" type="text" name="name" value="{{ old('name', $category->name) }}" required autofocus
                                class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <label for="description" class="block text-sm font-medium leading-5 text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="3" required
                                class="mt-1 form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md">@if(old('description')){{ old('description') }}@else{{ $category->description }}@endif</textarea>
                        </div>

                        <!-- Submit button -->
                        <div class="mt-6">
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150">
                                Save Changes
                            </button>
                        </div>
                    </form>

                    <!-- Cancel button -->
                    <div class="mt-4">
                        <a href="{{ route('storemanager.categories.index') }}"
                            class="text-indigo-600 hover:text-indigo-900 focus:outline-none focus:underline transition ease-in-out duration-150">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
