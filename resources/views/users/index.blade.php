	<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

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
                                        <td class="border border-gray-300 px-6 py-4 w-36 flex gap-2">
                                            <a href="{{ route('users.edit', $user) }}"
                                                class="px-4 py-2 text-sm font-medium text-white  rounded-lg btn-edit transition">
                                                Edit
                                            </a>
                                            <form action="{{ route('users.destroy', $user) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-600 transition"
                                                    onclick="return confirm('Delete {{ $user->name }}?')">
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
   <!-- After your table -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('table').DataTable({
            responsive: true, // Optional: for mobile-friendly tables
            columnDefs: [
                { orderable: false, targets: [3] } // Disable sorting for Actions column
            ]
        });
    });
</script>
</x-app-layout>
