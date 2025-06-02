<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium mb-6 text-gray-700">Edit User Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-container">
                            <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <!-- Name Input -->
                                <div>
                                    <label for="name">Full Name</label>
                                    <input type="text" name="name" id="name"
                                           value="{{ old('name', $user->name) }}"
                                           required
                                           class="w-full px-3 py-2 border rounded-md">
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email Input -->
                                <div>
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email"
                                           value="{{ old('email', $user->email) }}"
                                           required
                                           class="w-full px-3 py-2 border rounded-md">
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Password Input (Optional) -->
                                <div>
                                    <label for="password">New Password (Leave blank to keep current)</label>
                                    <input type="password" name="password" id="password"
                                           class="w-full px-3 py-2 border rounded-md">
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Password Confirmation -->
                                <div>
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="w-full px-3 py-2 border rounded-md">
                                </div>

                                <!-- Admin Checkbox -->
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_admin" id="is_admin"
                                           value="1"
                                           {{ $user->is_admin ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                    <label for="is_admin" class="ml-2">Administrator</label>
                                </div>

                                <!-- Submit Button -->
                                <div class="mt-6">
                                    <button type="submit"
                                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                        Update User
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
s
