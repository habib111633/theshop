<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Role
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-semibold mb-2">Role Name</label>
                        <input type="text" name="name" class="form-input w-full border rounded px-3 py-2" value="{{ old('name') }}" required>
                        @error('name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Permissions</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach($permissions as $permission)
                                <label class="inline-flex items-center">
                                    <input class="form-checkbox h-4 w-4 text-blue-600" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}">
                                    <span class="ml-2 text-gray-700">{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Create</button>
                        <a href="{{ route('roles.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
