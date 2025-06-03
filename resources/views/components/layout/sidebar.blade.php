<aside class="w-64 h-full bg-white shadow-md">
        <div class="p-4 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Navigation</h2>
    </div>
    <nav class="p-4 ">
        <ul class="space-y-2 w-48">
            <li>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center p-2 rounded hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-gray-100 font-medium' : '' }} text-gray-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
            </li>
            <li class="relative">
                <a href="{{ route('categories.index') }}"
                    class="flex items-center p-2 rounded hover:bg-gray-100 {{ request()->routeIs('categories.*') ? 'bg-gray-100 font-medium' : '' }} text-gray-700">
                    {{-- <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg> --}}
                    Categories

                </a>
                @if (request()->routeIs('categories.*'))
                    <div class=" bg-white shadow-md rounded w-48 text-sm z-10 mt-2">
                        <a href="{{ route('categories.create') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Create</a>
                        <a href="{{ route('categories.index') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Edit</a>
                    </div>
                @endif
            </li>
            <li class="relative">
    <a href="{{ route('users.index') }}"
        class="flex items-center p-2 rounded hover:bg-gray-100 {{ request()->routeIs('users.*') ? 'bg-gray-100 font-medium' : '' }} text-gray-700">
        Users
    </a>
    @if (request()->routeIs('users.*'))
        <div class="bg-white shadow-md rounded w-48 text-sm z-10 mt-2">
            <a href="{{ route('users.create') }}"
                class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Create User</a>
            <a href="{{ route('users.index') }}"
                class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Manage Users</a>
        </div>
    @endif
</li>

  <li class="relative">
    <a href="{{ route('conversations.index') }} "
        class="flex items-center p-2 rounded hover:bg-gray-100 text-gray-700">
        Messages
    </a>

</li>

            <!-- Add more navigation items as needed -->
        </ul>
    </nav>
</aside>
