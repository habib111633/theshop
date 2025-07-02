    <x-app-layout>


    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium mb-6">Manage users</h3>

                    <!-- Categories Table -->
                    <div class="overflow-x-auto">
                        <table
                            class="w-full table border-collapse border border-gray-300 bg-white text-sm rounded-lg shadow-md">
                            <thead class="bg-blue-100 text-gray-700 uppercase text-sm">
                                <tr>
                                    <th class="border border-gray-300 px-6 py-4 text-left font-semibold">user Id
                                    </th>
                                    <th class="border border-gray-300 px-6 py-4 text-left font-semibold">user Name
                                    </th>
                                    <th class="border border-gray-300 px-6 py-4 text-left font-semibold">user email
                                    </th>
                                    <th class="border border-gray-300 px-6 py-4 text-left font-semibold">Roles</th>
                                    <th class="border border-gray-300 px-6 py-4 text-left font-semibold w-36">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="hover:bg-blue-50">
                                        <td class="border border-gray-300 px-6 py-4">{{ $user->id }}</td>
                                        <td class="border border-gray-300 px-6 py-4">{{ $user->name }}</td>
                                        <td class="border border-gray-300 px-6 py-4">{{ $user->email }}</td>
                                        <td class="border border-gray-300 px-6 py-4">
                                            @foreach($user->roles as $role)
                                                <span class="inline-block bg-blue-100 text-blue-700 text-xs font-semibold rounded-full px-3 py-1 mr-1 mb-1">{{ ucfirst($role->name) }}</span>
                                            @endforeach
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4">
                                            <x-action-buttons
                                                :edit-url="route('users.edit', $user)"
                                                :delete-url="route('users.destroy', $user)"
                                                :delete-confirm="'Delete'"
                                                :delete-name="$user->name"
                                            />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
