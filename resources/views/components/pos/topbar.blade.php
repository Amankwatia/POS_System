@php
    $user = auth()->user();
    $isAdmin = $user?->hasRole(\App\Models\Role::ADMIN);
    $isManager = $user?->hasRole(\App\Models\Role::STORE_MANAGER);
    $isCashier = $user?->hasRole(\App\Models\Role::CASHIER);
    $roleLabel = $isAdmin ? 'Admin' : ($isManager ? 'Manager' : 'Cashier');
@endphp

<header class="fixed top-0 right-0 z-30 h-topbar bg-white border-b border-gray-200 transition-all duration-300 ease-in-out"
        :class="sidebarOpen ? 'lg:left-sidebar left-0' : 'lg:left-sidebar-collapsed left-0'">
    <div class="flex items-center justify-between h-full px-4 sm:px-6">
        <!-- Left: Mobile menu toggle & Page title -->
        <div class="flex items-center gap-4">
            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = true" 
                    class="lg:hidden p-2 -ml-2 rounded-lg hover:bg-gray-100 transition">
                <svg class="w-5 h-5 text-content-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Page Title -->
            <div>
                <h1 class="text-lg font-semibold text-content">Dashboard</h1>
            </div>
        </div>

        <!-- Right: User dropdown -->
        <div class="flex items-center gap-3">
            <!-- Quick Action (for Cashier) -->
            @if($isCashier)
                <a href="{{ route('cashier.pos') }}" 
                   class="hidden sm:inline-flex btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>New Sale</span>
                </a>
            @endif

            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        @click.outside="open = false"
                        class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 transition">
                    <!-- Avatar -->
                    <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm font-semibold">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                    <!-- Name & Role (hidden on mobile) -->
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-medium text-content leading-tight">{{ $user->name ?? 'User' }}</p>
                        <p class="text-xs text-content-muted">{{ $roleLabel }}</p>
                    </div>
                    <!-- Dropdown Arrow -->
                    <svg class="w-4 h-4 text-content-muted hidden sm:block transition-transform duration-200" 
                         :class="open ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-xl border border-gray-100 shadow-lg py-1 z-50">
                    
                    <!-- User Info (mobile) -->
                    <div class="sm:hidden px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-medium text-content">{{ $user->name ?? 'User' }}</p>
                        <p class="text-xs text-content-muted">{{ $user->email ?? '' }}</p>
                        <span class="inline-flex mt-1 badge badge-success text-xs">{{ $roleLabel }}</span>
                    </div>

                    <!-- Menu Items -->
                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center gap-3 px-4 py-2.5 text-sm text-content-secondary hover:bg-gray-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profile
                    </a>

                    @if($isAdmin)
                        <a href="{{ route('admin.roles.index') }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-content-secondary hover:bg-gray-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Settings
                        </a>
                    @endif

                    <div class="border-t border-gray-100 my-1"></div>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-danger hover:bg-danger-light transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
