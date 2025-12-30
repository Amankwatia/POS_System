<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">User Management</p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users</h2>
            </div>
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-800">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl">
                <div class="border-b border-gray-100 px-4 py-3">
                    <h3 class="text-sm font-semibold text-gray-900">Add Manager or Cashier</h3>
                </div>
                <form method="POST" action="{{ route('admin.users.store') }}" class="p-4 grid gap-4 sm:grid-cols-2">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input name="name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input name="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input name="password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        @error('password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            <option value="" disabled selected>Select role</option>
                            @foreach ($assignableRoles as $role)
                                <option value="{{ $role->slug }}">{{ ucfirst(str_replace('_', ' ', $role->slug)) }}</option>
                            @endforeach
                        </select>
                        @error('role')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500">Create User</button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl">
                <div class="border-b border-gray-100 px-4 py-3 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">All Users</h3>
                    <p class="text-xs text-gray-500">Admins, managers, and cashiers</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Roles</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($users as $user)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $user->name }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $user->email }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $user->roles->pluck('slug')->implode(', ') ?: '—' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $user->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-sm text-gray-500">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
