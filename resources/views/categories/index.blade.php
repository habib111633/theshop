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
                                        <td class="border border-gray-300 px-6 py-4 flex gap-2">
                                            <a href="{{ route('categories.edit', $category) }}"
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                                Edit
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-600 transition"
                                                    onclick="return confirm('Delete {{ $category->name }}?')">
                                                    Delete
                                                </button>
                                            </form>
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
