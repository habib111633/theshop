<aside class="w-64 h-full bg-white shadow-md">
        <div class="p-4 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Navigation</h2>
    </div>
    <nav class="p-4 ">
        <ul class="space-y-2 w-48">
            @can('view dashboard')
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
            @endcan
            @can('view categories')
            <li class="relative">
                <a href="{{ route('categories.index') }}"
                    class="flex items-center p-2 rounded hover:bg-gray-100 {{ request()->routeIs('categories.*') ? 'bg-gray-100 font-medium' : '' }} text-gray-700">
                    <svg class="w-5 h-5 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    Categories
                </a>
                @if (request()->routeIs('categories.*'))
                    <div class="bg-white shadow-md rounded w-48 text-sm z-10 mt-2">
                        <a href="{{ route('categories.create') }}"
                            class="flex items-center px-4 py-2 text-blue-600 hover:bg-blue-50 hover:text-blue-800 rounded transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Category
                        </a>
                        <a href="{{ route('categories.index') }}"
                            class="flex items-center px-4 py-2 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-800 rounded transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />
                            </svg>
                            Manage Categories
                        </a>
                    </div>
                @endif
            </li>
            @endcan
            @can('view products')
            <li class="relative">
                <a href="{{ route('products.index') }}"
                    class="flex items-center p-2 rounded hover:bg-gray-100 {{ request()->routeIs('products.*') ? 'bg-gray-100 font-medium' : '' }} text-gray-700">
                    <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Products
                </a>
                @if (request()->routeIs('products.*'))
                    <div class="bg-white shadow-md rounded w-48 text-sm z-10 mt-2">
                        <a href="{{ route('products.create') }}"
                            class="flex items-center px-4 py-2 text-blue-600 hover:bg-blue-50 hover:text-blue-800 rounded transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Product
                        </a>
                        <a href="{{ route('products.index') }}"
                            class="flex items-center px-4 py-2 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-800 rounded transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />
                            </svg>
                            Manage Products
                        </a>
                    </div>
                @endif
            </li>
            @endcan
            @can('view orders')
            <li class="relative">
                <a href="{{ route('admin.orders.index') }}"
                    class="flex items-center p-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-100 font-medium' : '' }} text-gray-700">
                    <svg class="w-5 h-5 mr-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18M9 3v18m6-18v18M4 21h16a1 1 0 001-1V7a1 1 0 00-1-1H4a1 1 0 00-1 1v13a1 1 0 001 1z" />
                    </svg>
                    Orders
                </a>
            </li>

            @endcan
            @can('view users')
            <li class="relative">
                <a href="{{ route('users.index') }}"
                    class="flex items-center p-2 rounded hover:bg-gray-100 {{ request()->routeIs('users.*') ? 'bg-gray-100 font-medium' : '' }} text-gray-700">
                    <svg class="w-5 h-5 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Users
                </a>
                @if (request()->routeIs('users.*'))
                    <div class="bg-white shadow-md rounded w-48 text-sm z-10 mt-2">
                        <a href="{{ route('users.create') }}"
                            class="flex items-center px-4 py-2 text-blue-600 hover:bg-blue-50 hover:text-blue-800 rounded transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add User
                        </a>
                        <a href="{{ route('users.index') }}"
                            class="flex items-center px-4 py-2 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-800 rounded transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />
                            </svg>
                            Manage Users
                        </a>
                    </div>
                @endif
            </li>

            @endcan
            @can('view conversations')
            <li class="relative">
                <a href="{{ route('conversations.index') }}"
                    class="flex items-center p-2 rounded hover:bg-gray-100 text-gray-700">
                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
                    </svg>
                    Messages
                </a>
            </li>
            @endcan
            @can('view roles')
            <li class="relative">
                <a href="{{ route('roles.index') }}"
                    class="flex items-center p-2 rounded hover:bg-gray-100 {{ request()->routeIs('roles.*') ? 'bg-gray-100 font-medium' : '' }} text-gray-700">
                    <svg class="w-5 h-5 mr-3 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14v7m-7-3a7 7 0 0114 0" />
                    </svg>
                    Manage Roles
                </a>
                @if (request()->routeIs('roles.*'))
                    <div class="bg-white shadow-md rounded w-48 text-sm z-10 mt-2">
                        <a href="{{ route('roles.create') }}"
                            class="flex items-center px-4 py-2 text-blue-600 hover:bg-blue-50 hover:text-blue-800 rounded transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Role
                        </a>
                        <a href="{{ route('roles.index') }}"
                            class="flex items-center px-4 py-2 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-800 rounded transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />
                            </svg>
                            Manage Roles
                        </a>
                    </div>
                @endif
            </li>

            @endcan
            <!-- Add more navigation items as needed -->
        </ul>
    </nav>
</aside>
