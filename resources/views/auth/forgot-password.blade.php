<x-guest-layout>
    <h2 class="font-display text-2xl font-bold text-soil mb-4 text-center">Reset Password</h2>

    <div class="mb-6 text-sm text-bark text-center">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-primary font-bold bg-primary-muted p-3 rounded-lg" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-bold text-soil mb-2">{{ __('Email') }}</label>
            <input id="email" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" type="email" name="email" value="{{ old('email') }}" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
        </div>

        <div class="pt-2">
            <button class="btn-primary w-full justify-center text-lg py-3">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>

        <p class="text-center text-sm text-bark mt-6">
            Remember your password? 
            <a href="{{ route('login') }}" class="font-bold text-primary hover:text-primary-dark transition-colors">Log in here</a>
        </p>
    </form>
</x-guest-layout>
