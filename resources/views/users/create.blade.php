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
                    <h3 class="text-lg font-medium mb-4">Add User</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Add/Edit User Form -->

                        <div class="form-container">
<form enctype="multipart/form-data" method="POST" action="{{ route('users.store') }}">
            @csrf <!-- CSRF Protection -->



        <!-- Name Field -->
        <input type="text" placeholder="Enter full name" name="name" value="{{ old('name') }}">
        @if ($errors->has('name'))
            <span class="text-danger">
                {{ $errors->first('name') }}
            </span>
        @endif

        <!-- Email Field -->
        <input type="email" placeholder="Enter email address" name="email" value="{{ old('email') }}">
        @if ($errors->has('email'))
            <span class="text-danger">
                {{ $errors->first('email') }}
            </span>
        @endif

        <!-- Password Field -->
        <input type="password" placeholder="Enter password" name="password">
        @if ($errors->has('password'))
            <span class="text-danger">
                {{ $errors->first('password') }}
            </span>
        @endif

        <!-- Password Confirmation -->
        <input type="password" placeholder="Confirm password" name="password_confirmation">

        <!-- Admin Checkbox (optional) -->
        <div class="checkbox-container">
            <label>
                <input type="checkbox" name="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}>
                Make this user an admin
            </label>
        </div>

        <button type="submit">Create User</button>
    </form>

    @if (session()->has('success'))
        <div class="alert alert-success">
            <p style="color:#fff">{{ session()->get('success') }}</p>
        </div>
    @endif
</div>

                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
