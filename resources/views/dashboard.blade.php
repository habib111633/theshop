<x-app-layout>
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Graphs Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Sales Over Time Chart -->
                <div class="bg-white shadow rounded-lg p-6 flex flex-col items-center">
                    <h3 class="text-lg font-semibold mb-4">Sales Over Time</h3>
                    <div class="w-full max-w-md mx-auto">
                        <canvas id="salesChart" height="80"></canvas>
                    </div>
                </div>
                <!-- Orders by Status Chart -->
                <div class="bg-white shadow rounded-lg p-6 flex flex-col items-center">
                    <h3 class="text-lg font-semibold mb-4">Orders by Status</h3>
                    <div class="w-full max-w-xs mx-auto">
                        <canvas id="ordersStatusChart" height="80"></canvas>
                    </div>
                </div>
            </div>
            <!-- Widgets Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Sales Summary Widget -->
                <div class="bg-white shadow rounded-lg p-6" id="sales-summary-widget">
                    <h3 class="text-lg font-semibold mb-2">Sales Summary</h3>
                    <div class="mb-2 text-2xl font-bold" id="sales-month">${{ number_format($salesMonth ?? 0, 2) }}</div>
                    <div class="text-gray-500 text-sm">This Month</div>
                    <div class="mt-4 flex justify-between text-sm">
                        <span>Today: <span class="font-semibold" id="sales-today">${{ number_format($salesToday ?? 0, 2) }}</span></span>
                        <span>Total: <span class="font-semibold" id="sales-total">${{ number_format($salesTotal ?? 0, 2) }}</span></span>
                    </div>
                </div>
                <!-- Orders Overview Widget -->
                <div class="bg-white shadow rounded-lg p-6" id="orders-overview-widget">
                    <h3 class="text-lg font-semibold mb-2">Orders Overview</h3>
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between"><span>Pending:</span> <span class="font-bold" id="orders-pending">{{ $ordersByStatus['Pending'] ?? 0 }}</span></div>
                        <div class="flex justify-between"><span>Processing:</span> <span class="font-bold" id="orders-processing">{{ $ordersByStatus['Processing'] ?? 0 }}</span></div>
                        <div class="flex justify-between"><span>Completed:</span> <span class="font-bold" id="orders-completed">{{ $ordersByStatus['Completed'] ?? 0 }}</span></div>
                        <div class="flex justify-between"><span>Cancelled:</span> <span class="font-bold" id="orders-cancelled">{{ $ordersByStatus['Cancelled'] ?? 0 }}</span></div>
                    </div>
                </div>
                <!-- Users Widget -->
                <div class="bg-white shadow rounded-lg p-6" id="users-widget">
                    <h3 class="text-lg font-semibold mb-2">Users</h3>
                    <div class="mb-2 text-2xl font-bold" id="users-total">{{ $usersTotal ?? 0 }}</div>
                    <div class="text-gray-500 text-sm">Total Users</div>
                    <div class="mt-4 text-sm">New This Month: <span class="font-semibold" id="users-new-month">{{ $usersNewThisMonth ?? 0 }}</span></div>
                </div>
                <!-- Products Widget -->
                <div class="bg-white shadow rounded-lg p-6" id="products-widget">
                    <h3 class="text-lg font-semibold mb-2">Products</h3>
                    <div class="mb-2 text-2xl font-bold" id="products-total">{{ $productsTotal ?? 0 }}</div>
                    <div class="text-gray-500 text-sm">Total Products</div>
                    <div class="mt-4 text-sm">Low Stock: <span class="font-semibold" id="products-low-stock">{{ $productsLowStock ?? 0 }}</span></div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Orders Widget -->
                <div class="bg-white shadow rounded-lg p-6 lg:col-span-2">
                    <h3 class="text-lg font-semibold mb-4">Recent Orders</h3>
                    <table class="min-w-full text-sm" id="recent-orders-table">
                        <thead>
                            <tr class="text-left border-b">
                                <th class="py-2">Order #</th>
                                <th class="py-2">Customer</th>
                                <th class="py-2">Status</th>
                                <th class="py-2">Total</th>
                                <th class="py-2">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders ?? [] as $order)
                            <tr>
                                <td class="py-2">#{{ $order->id }}</td>
                                <td class="py-2">{{ $order->user->name ?? 'N/A' }}</td>
                                <td class="py-2"><span class="px-2 py-1 rounded {{ $order->status == 'completed' ? 'bg-green-100 text-green-700' : ($order->status == 'processing' ? 'bg-yellow-100 text-yellow-700' : ($order->status == 'new' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700')) }}">{{ ucfirst($order->status) }}</span></td>
                                <td class="py-2">${{ number_format($order->total, 2) }}</td>
                                <td class="py-2">{{ $order->created_at->format('Y-m-d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Quick Links Widget -->
                <div class="bg-white shadow rounded-lg p-6 flex flex-col gap-4">
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    @can('create products')
                    <a href="{{ route('admin.products.create') }}" class="bg-[#ff0042] text-white px-4 py-2 rounded font-semibold text-center hover:bg-opacity-90 transition">Add Product</a>
                    @endcan
                    @can('view orders')
                    <a href="{{ route('admin.orders.index') }}" class="bg-gray-100 text-gray-800 px-4 py-2 rounded font-semibold text-center hover:bg-gray-200 transition">View Orders</a>
                    @endcan
                    @can('view users')
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-100 text-gray-800 px-4 py-2 rounded font-semibold text-center hover:bg-gray-200 transition">Manage Users</a>
                    @endcan
                    <a href="{{ route('shop') }}" class="bg-gray-100 text-gray-800 px-4 py-2 rounded font-semibold text-center hover:bg-gray-200 transition">View Shop</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Fallback for non-JS users (server-rendered data)
    const fallbackSalesLabels = {!! json_encode($salesLabels ?? []) !!};
    const fallbackSalesData = {!! json_encode($salesData ?? []) !!};
    const fallbackOrdersStatusLabels = {!! json_encode(array_keys($ordersByStatus ?? [])) !!};
    const fallbackOrdersStatusData = {!! json_encode(array_values($ordersByStatus ?? [])) !!};
    const fallbackSalesToday = {!! json_encode($salesToday ?? 0) !!};
    const fallbackSalesMonth = {!! json_encode($salesMonth ?? 0) !!};
    const fallbackSalesTotal = {!! json_encode($salesTotal ?? 0) !!};
    const fallbackOrdersByStatus = {!! json_encode($ordersByStatus ?? []) !!};
    const fallbackUsersTotal = {!! json_encode($usersTotal ?? 0) !!};
    const fallbackUsersNewThisMonth = {!! json_encode($usersNewThisMonth ?? 0) !!};
    const fallbackProductsTotal = {!! json_encode($productsTotal ?? 0) !!};
    const fallbackProductsLowStock = {!! json_encode($productsLowStock ?? 0) !!};
    const fallbackRecentOrders = {!! json_encode($recentOrders ?? []) !!};

    function renderCharts(salesLabels, salesData, ordersStatusLabels, ordersStatusData) {
        // Sales Over Time (Line Chart)
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'Sales',
                    data: salesData,
                    borderColor: '#ff0042',
                    backgroundColor: 'rgba(255,0,66,0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: '#ff0042',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#222',
                        bodyColor: '#222',
                        borderColor: '#ff0042',
                        borderWidth: 1,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#888', font: { size: 13 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f3f3' },
                        ticks: { color: '#888', font: { size: 13 } }
                    }
                }
            }
        });
        // Orders by Status (Doughnut Chart)
        const ordersStatusCtx = document.getElementById('ordersStatusChart').getContext('2d');
        new Chart(ordersStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ordersStatusLabels,
                datasets: [{
                    data: ordersStatusData,
                    backgroundColor: [
                        '#d1d5db', // soft gray (Pending)
                        '#f59e42', // orange (Processing)
                        '#22c55e', // green (Completed)
                        '#ef4444'  // red (Cancelled)
                    ],
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#444', font: { size: 13 } } },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#222',
                        bodyColor: '#222',
                        borderColor: '#888',
                        borderWidth: 1,
                    }
                }
            }
        });
    }

    function updateWidgets(data) {
        // Sales Summary
        document.getElementById('sales-today').textContent = `$${parseFloat(data.salesToday).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}`;
        document.getElementById('sales-month').textContent = `$${parseFloat(data.salesMonth).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}`;
        document.getElementById('sales-total').textContent = `$${parseFloat(data.salesTotal).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}`;
        // Orders Overview
        document.getElementById('orders-pending').textContent = data.ordersByStatus['Pending'] ?? 0;
        document.getElementById('orders-processing').textContent = data.ordersByStatus['Processing'] ?? 0;
        document.getElementById('orders-completed').textContent = data.ordersByStatus['Completed'] ?? 0;
        document.getElementById('orders-cancelled').textContent = data.ordersByStatus['Cancelled'] ?? 0;
        // Users
        document.getElementById('users-total').textContent = data.usersTotal ?? 0;
        document.getElementById('users-new-month').textContent = data.usersNewThisMonth ?? 0;
        // Products
        document.getElementById('products-total').textContent = data.productsTotal ?? 0;
        document.getElementById('products-low-stock').textContent = data.productsLowStock ?? 0;
        // Recent Orders Table
        const tbody = document.querySelector('#recent-orders-table tbody');
        tbody.innerHTML = '';
        (data.recentOrders || []).forEach(order => {
            let statusClass = '';
            switch ((order.status || '').toLowerCase()) {
                case 'completed': statusClass = 'bg-green-100 text-green-700'; break;
                case 'processing': statusClass = 'bg-yellow-100 text-yellow-700'; break;
                case 'new': statusClass = 'bg-blue-100 text-blue-700'; break;
                default: statusClass = 'bg-red-100 text-red-700'; break;
            }
            tbody.innerHTML += `<tr>
                <td class="py-2">#${order.id}</td>
                <td class="py-2">${order.user?.name ?? 'N/A'}</td>
                <td class="py-2"><span class="px-2 py-1 rounded ${statusClass}">${order.status ? order.status.charAt(0).toUpperCase() + order.status.slice(1) : ''}</span></td>
                <td class="py-2">$${parseFloat(order.total).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                <td class="py-2">${order.created_at ? order.created_at.substring(0, 10) : ''}</td>
            </tr>`;
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // If the page is loaded as JSON (e.g. via browser navigation cache), reload to get HTML
        if (document.contentType === 'application/json' || document.body.innerText.trim().startsWith('{')) {
            window.location.href = window.location.pathname;
            return;
        }

        // AJAX fetch for dashboard data
        fetch(window.location.pathname, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                window.location.href = window.location.pathname;
                return Promise.reject('Invalid content type');
            }
            // If response is JSON but not an AJAX request, reload as HTML
            if (window.location.search.indexOf('force_html') === -1 && response.url === window.location.href && contentType.includes('application/json')) {
                window.location.href = window.location.pathname + '?force_html=1';
                return Promise.reject('Force HTML reload');
            }
            return response.json();
        })
        .then(data => {
            renderCharts(data.salesLabels, data.salesData, data.ordersByStatusLabels, data.ordersByStatusData);
            updateWidgets(data);
        })
        .catch(error => {
            // Fallback to server-rendered data if AJAX fails
            renderCharts(fallbackSalesLabels, fallbackSalesData, fallbackOrdersStatusLabels, fallbackOrdersStatusData);
            updateWidgets({
                salesToday: fallbackSalesToday,
                salesMonth: fallbackSalesMonth,
                salesTotal: fallbackSalesTotal,
                ordersByStatus: fallbackOrdersByStatus,
                usersTotal: fallbackUsersTotal,
                usersNewThisMonth: fallbackUsersNewThisMonth,
                productsTotal: fallbackProductsTotal,
                productsLowStock: fallbackProductsLowStock,
                recentOrders: fallbackRecentOrders,
            });
        });

        // Handle back/forward navigation reliably
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.href = window.location.pathname;
            }
        });
    });
    </script>
</x-app-layout>
