<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-primary font-bold bg-primary-muted p-3 rounded-lg" :status="session('status')" />

    <h2 class="font-display text-2xl font-bold text-soil mb-6 text-center">Welcome Back</h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-bold text-soil mb-2">{{ __('Email') }}</label>
            <input id="email" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-bold text-soil mb-2">{{ __('Password') }}</label>
            <input id="password" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-stone text-primary shadow-sm focus:ring-primary/20 cursor-pointer" name="remember">
                <span class="ms-2 text-sm text-bark cursor-pointer">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-bold text-primary hover:text-primary-dark transition-colors" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button class="btn-primary w-full justify-center text-lg py-3">
                {{ __('Log in') }}
            </button>
        </div>

        <p class="text-center text-sm text-bark mt-6">
            Don't have an account? 
            <a href="{{ route('register') }}" class="font-bold text-primary hover:text-primary-dark transition-colors">Register here</a>
        </p>
    </form>
</x-guest-layout>
