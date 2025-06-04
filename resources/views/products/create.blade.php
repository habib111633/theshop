<x-app-layout>


    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @props(['product' => null, 'categories'])

                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-2xl font-semibold text-gray-800">{{ $product ? 'Edit' : 'Create' }} Product
                            </h2>
                        </div>

                        <form method="POST"
                            action="{{ $product ? route('products.update', $product) : route('products.store') }}"
                            enctype="multipart/form-data" class="px-6 py-4">
                            @csrf
                            @if ($product)
                                @method('PUT')
                            @endif

                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Product
                                        Name</label>
                                    <input type="text" name="name" id="name"
                                        value="{{ old('name', $product->name ?? '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" id="description" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('description', $product->description ?? '') }}</textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <label for="price"
                                            class="block text-sm font-medium text-gray-700">Price</label>
                                        <input type="number" step="0.01" name="price" id="price"
                                            value="{{ old('price', $product->price ?? '') }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        @error('price')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label for="stock"
                                                class="block text-sm font-medium text-gray-700">Stocks</label>
                                            <input type="number" step="0.01" name="stock" id="stock"
                                                value="{{ old('stock', $product->stock ?? '') }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            @error('stock')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="category_id"
                                                class="block text-sm font-medium text-gray-700">Category</label>
                                            <select name="category_id" id="category_id"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                                <option value="">Select a category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="image" class="block text-sm font-medium text-gray-700">Product
                                            Image</label>
                                        <input type="file" name="image" id="image"
                                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        @error('image')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror

                                        @if ($product && $product->media->first())
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $product->media->first()->path) }}"
                                                    alt="Current product image"
                                                    class="h-32 w-32 object-cover rounded-md">
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <a href="{{ route('products.index') }}"
                                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Cancel</a>
                                    <button type="submit"
                                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Save</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
