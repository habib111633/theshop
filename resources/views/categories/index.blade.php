<x-app-layout>


    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium mb-6">Manage Categories</h3>

                    <!-- Categories Table -->
                    <div class="overflow-x-auto">
                        <table
                            class="w-full border-collapse border border-gray-300 bg-white text-sm rounded-lg shadow-md">
                            <thead class="bg-blue-100 text-gray-700 uppercase text-sm">
                                <tr>
                                    <th class="border border-gray-300 px-6 py-4 text-left font-semibold">Category Name
                                    </th>
                                    <th class="border border-gray-300 px-6 py-4 text-left font-semibold w-36">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr class="hover:bg-blue-50">
                                        <td class="border border-gray-300 px-6 py-4">{{ $category->name }}</td>
                                        <td class="border border-gray-300 px-6 py-4">
                                            <x-action-buttons
                                                :edit-url="route('categories.edit', $category)"
                                                :delete-url="route('categories.destroy', $category)"
                                                :delete-confirm="'Delete'"
                                                :delete-name="$category->name"
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
