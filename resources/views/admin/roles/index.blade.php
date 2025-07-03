<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Roles Management
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold">Roles</h1>
                    @can('create roles')
                    <a href="{{ route('admin.roles.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Create Role</a>
                    @endcan
                </div>
                @if(session('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif
                <table class="min-w-full bg-white border rounded">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2">Name</th>
                            <th class="border px-4 py-2">Permissions</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                        <tr>
                            <td class="border px-4 py-2">{{ $role->name }}</td>
                            <td class="border px-4 py-2">
                                @foreach($role->permissions as $permission)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1 mb-1">{{ $permission->name }}</span>
                                @endforeach
                            </td>
                            <td class="border px-4 py-2 whitespace-nowrap">
                                @canany(['edit roles', 'delete roles'])
                                    <x-role-action-buttons
                                        :edit-url="route('admin.roles.edit', $role)"
                                        :delete-url="route('admin.roles.destroy', $role)"
                                        :delete-confirm="'Are you sure you want to delete this role?'"
                                        :delete-name="$role->name"
                                    />
                                @endcanany
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">No roles found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
