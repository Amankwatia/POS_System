<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-white">Welcome back</h1>
        <p class="text-slate-400 mt-1">Sign in to your account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Email</label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
                autocomplete="username"
                class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                placeholder="you@example.com"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-2">Password</label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password"
                class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                placeholder="••••••••"
            >
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    name="remember"
                    class="w-4 h-4 bg-slate-700 border-slate-600 rounded text-indigo-500 focus:ring-indigo-500 focus:ring-offset-0 focus:ring-offset-slate-800"
                >
                <span class="ml-2 text-sm text-slate-400">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button 
            type="submit"
            class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-300 hover:shadow-indigo-500/50 hover:-translate-y-0.5"
        >
            Sign In
        </button>
    </form>

    <!-- Contact -->
    <div class="mt-6 text-center">
        <p class="text-slate-400 text-sm">
            Need help? 
            <a href="mailto:brightamankwatia@gmail.com" class="text-indigo-400 hover:text-indigo-300 transition-colors">Contact support</a>
        </p>
    </div>
</x-guest-layout>
