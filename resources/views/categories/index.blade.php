<x-app-layout>


    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-white">
                            <div class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:items-center sm:justify-between">
                                <!-- Left side - Heading -->
                                <div class="flex-1">
                                    <h2 class="text-2xl font-semibold text-gray-800">Category Management</h2>
                                    <p class="text-sm text-gray-500 mt-1">Manage your product categories</p>
                                </div>
                                <!-- Right side - Action Buttons -->
                                <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-3">
                                    @can('create categories')
                                    <a href="{{ route('admin.categories.create') }}"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition duration-200 inline-flex items-center justify-center shadow-sm hover:shadow-md">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Add Category
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto mt-6">
                            <table id="categories-table" class="min-w-full table divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($categories as $category)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex justify-center items-center space-x-2">
                                                <!-- Edit Button with Tooltip -->
                                                @can('edit categories')
                                                <div class="relative group">
                                                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-500 hover:text-indigo-700 transition-colors duration-200">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <span class="absolute z-10 invisible group-hover:visible bg-gray-800 text-white text-xs rounded py-1 px-2 -bottom-6 left-1/2 transform -translate-x-1/2 whitespace-nowrap">Edit</span>
                                                </div>
                                                @endcan
                                                @can('delete categories')
                                                <!-- Delete Button with Tooltip -->
                                                <div class="relative group">
                                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors duration-200" onclick="return confirm('Are you sure you want to delete this category?')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                        <span class="absolute z-10 invisible group-hover:visible bg-gray-800 text-white text-xs rounded py-1 px-2 -bottom-7 left-1/2 transform -translate-x-1/2 whitespace-nowrap">Delete</span>
                                                    </form>
                                                </div>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @push('scripts')
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                if (window.jQuery && $.fn.DataTable) {
                                    $('#categories-table').DataTable();
                                }
                            });
                        </script>
                        @endpush
                    </div>
                </div>
            </div>
        </div>
    </div>

                    @push('scripts')
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            if (window.jQuery && $.fn.DataTable) {
                                $('#categories-table').DataTable();
                            }
                        });
                    </script>
                    @endpush
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
