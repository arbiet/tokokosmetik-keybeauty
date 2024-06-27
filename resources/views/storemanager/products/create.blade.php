<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Add Product') }}
                </h2>
                <div class="text-gray-900">
                        {{ __("You're logged in as Store Manager!") }} 
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('storemanager.products.store') }}" enctype="multipart/form-data">
                        @csrf
                    
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium leading-5 text-gray-700">Name</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                        </div>
                    
                        <!-- Description -->
                        <div class="mt-4">
                            <label for="description" class="block text-sm font-medium leading-5 text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="3" required
                                class="mt-1 form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md">{{ old('description') }}</textarea>
                        </div>

                        <!-- Price -->
                        <div class="mt-4">
                            <label for="price" class="block text-sm font-medium leading-5 text-gray-700">Price</label>
                            <input id="price" type="number" name="price" value="{{ old('price') }}" required
                                class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md  @error('price') border-red-500 @enderror">
                                @error('price')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                        </div>
                    
                        <!-- Stock -->
                        <div class="mt-4">
                            <label for="stock" class="block text-sm font-medium leading-5 text-gray-700">Stock</label>
                            <input id="stock" type="number" name="stock" value="{{ old('stock') }}" required
                                class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md @error('stock') border-red-500 @enderror">
                                @error('stock')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                        </div>

                        <!-- Category -->
                        <div class="mt-4">
                            <label for="category" class="block text-sm font-medium leading-5 text-gray-700">Category</label>
                            <select id="category" name="category_id" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Weight -->
                        <div class="mt-4">
                            <label for="weight" class="block text-sm font-medium leading-5 text-gray-700">Weight (gram)</label>
                            <input id="weight" type="number" step="0.01" name="weight" value="{{ old('weight') }}" required
                                class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md @error('weight') border-red-500 @enderror">
                            @error('weight')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Length -->
                        <div class="mt-4">
                            <label for="length" class="block text-sm font-medium leading-5 text-gray-700">Length (mm)</label>
                            <input id="length" type="number" step="0.01" name="length" value="{{ old('length') }}" 
                                class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md @error('length') border-red-500 @enderror">
                            @error('length')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Width -->
                        <div class="mt-4">
                            <label for="width" class="block text-sm font-medium leading-5 text-gray-700">Width (mm)</label>
                            <input id="width" type="number" step="0.01" name="width" value="{{ old('width') }}" 
                                class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md @error('width') border-red-500 @enderror">
                            @error('width')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Height -->
                        <div class="mt-4">
                            <label for="height" class="block text-sm font-medium leading-5 text-gray-700">Height (mm)</label>
                            <input id="height" type="number" step="0.01" name="height" value="{{ old('height') }}" 
                                class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md @error('height') border-red-500 @enderror">
                            @error('height')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mt-4">
                            <label for="status" class="block text-sm font-medium leading-5 text-gray-700">Status</label>
                            <select id="status" name="status" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md">
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                            </select>
                        </div>

                        <!-- Image -->
                        <div class="mt-4">
                            <label for="image" class="block text-sm font-medium leading-5 text-gray-700">Image</label>
                            <input id="image" type="file" name="image" accept="image/*" class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md @error('image') border-red-500 @enderror">
                            @error('image')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    
                        <!-- Submit button -->
                        <div class="mt-6">
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150">
                                Add Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
