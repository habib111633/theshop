<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium mb-6 text-gray-700">Edit Category</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div class="form-container">

                            <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <!-- Category Name Input -->

                                <label for="name" class="">Category
                                    Name</label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $category->name) }}" required>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                <!-- Submit Button -->
                                <div>
                                    <button type="submit">
                                        Update Category
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
