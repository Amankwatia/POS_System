<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Roles</p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Roles & Permissions</h2>
            </div>
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl">
                <div class="border-b border-gray-100 px-4 py-3 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Available Roles</h3>
                    <span class="text-xs text-gray-500">Fixed set</span>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($roles as $role)
                        <div class="px-4 py-3 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $role->name }}</p>
                                <p class="text-xs text-gray-500">Slug: {{ $role->slug }}</p>
                            </div>
                            <p class="text-xs text-gray-500">Created {{ $role->created_at->format('Y-m-d') }}</p>
                        </div>
                    @empty
                        <p class="px-4 py-3 text-sm text-gray-500">No roles found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
