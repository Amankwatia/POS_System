<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Orders</p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Orders</h2>
            </div>
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl">
                <div class="border-b border-gray-100 px-4 py-3 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Recent Orders</h3>
                    <p class="text-xs text-gray-500">Newest first</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Payments</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($orders as $order)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">#{{ $order->id }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $order->user?->email ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600 capitalize">{{ $order->status }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">₵{{ number_format($order->total, 2) }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $order->payments->count() }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $order->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-sm text-gray-500">No orders yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
