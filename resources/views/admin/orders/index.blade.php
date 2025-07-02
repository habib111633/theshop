<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Orders Management
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <form method="GET" class="mb-4 flex flex-wrap gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by ID, name, email" class="border rounded px-2 py-1" />
                    <select name="status" class="border rounded px-8 py-1">
                        <option value="">All Statuses</option>
                        @foreach(['pending', 'processing', 'completed', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected(request('status') == $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Filter</button>
                </form>
                <table class="min-w-full bg-white border rounded">
                    <thead>
                        <tr>
                            <th><a href="{{ route('admin.orders.index', array_merge(request()->all(), ['sort' => 'id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">Order #</a></th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="border px-4 py-2">#{{ $order->id }}</td>
                            <td class="border px-4 py-2">
                                @if($order->user)
                                    {{ $order->user->name }}<br>
                                    <span class="text-xs text-gray-500">{{ $order->user->email }}</span>
                                @else
                                    Guest ({{ $order->billing_name }})<br>
                                    <span class="text-xs text-gray-500">{{ $order->billing_email }}</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2">${{ number_format($order->total, 2) }}</td>
                            <td class="border px-4 py-2">
                                <span class="px-2 py-1 rounded text-xs
                                    @if($order->status == 'completed') bg-green-100 text-green-800
                                    @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif
                                ">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="border px-4 py-2">{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td class="border px-4 py-2">
                                <x-admin-order-action-buttons
                                    :view-url="route('admin.orders.show', $order)"
                                    :edit-url="route('admin.orders.edit', $order)"
                                    :delete-url="route('admin.orders.destroy', $order)"
                                    :delete-confirm="'Are you sure you want to delete this order?'"
                                    :delete-name="'#' . $order->id"
                                />
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
