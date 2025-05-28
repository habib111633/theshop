<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium mb-4">Add category</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Add/Edit Category Form -->

                        <div class="form-container">

                            <form action="{{ route('categories.store') }}" method="POST">

                                @csrf <!-- CSRF Protection -->
                                <input type="text" placeholder="Enter category name" name='name'>
                                @if ($errors->has('name'))
                                    <span class="text-danger">
                                        {{ $errors->first('name') }}
                                    </span>
                                @endif
                                <button type="submit">Add Category</button>
                            </form>
                            @if (session()->has('success'))
                                <div class="alert alert-success">
                                    <p style=" color:#fff">{{ session()->get('success') }}</p>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
