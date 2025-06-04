<x-app-layout>


    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-2xl font-semibold text-gray-800">Product Details</h2>
                            <div>
                                <a href="{{ route('products.edit', $product) }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-300 mr-2">Edit</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition duration-300"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </div>

                        <div class="px-6 py-4">
                            <div class="flex flex-col md:flex-row gap-8">
                                <div class="md:w-1/3">
                                    @if ($product->media->first())
                                        <img src="{{ asset('storage/' . $product->media->first()->path) }}"
                                            alt="{{ $product->name }}" class="w-full h-auto rounded-lg shadow-md">
                                    @else
                                        <div
                                            class="bg-gray-200 w-full h-64 flex items-center justify-center rounded-lg">
                                            <span class="text-gray-500">No image</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="md:w-2/3">
                                    <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $product->name }}</h3>
                                    <div class="flex items-center mb-4">
                                        <span
                                            class="text-xl font-semibold text-blue-600">${{ number_format($product->price, 2) }}</span>
                                        <span
                                            class="ml-4 px-3 py-1 bg-gray-100 text-gray-800 text-sm rounded-full">{{ $product->category->name }}</span>
                                    </div>

                                    <div class="prose max-w-none">
                                        <p class="text-gray-700">{{ $product->description }}</p>
                                    </div>

                                    <div class="mt-6 pt-6 border-t border-gray-200">
                                        <h4 class="text-sm font-medium text-gray-500">Seller Information</h4>
                                        <p class="mt-1 text-sm text-gray-900">{{ $product->seller->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
