<x-guest-layout>
    <h2 class="font-display text-2xl font-bold text-soil mb-6 text-center">Create an Account</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-bold text-soil mb-2">{{ __('Name') }}</label>
            <input id="name" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-bold text-soil mb-2">{{ __('Email') }}</label>
            <input id="email" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Phone -->
        <div>
            <label for="phone" class="block text-sm font-bold text-soil mb-2">{{ __('Phone') }}</label>
            <input id="phone" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" type="text" name="phone" value="{{ old('phone') }}" required autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- County -->
        <div>
            <label for="county" class="block text-sm font-bold text-soil mb-2">{{ __('County') }}</label>
            <select id="county" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" name="county" required>
                <option value="" disabled selected>{{ __('Select your county') }}</option>
                @foreach(\App\Enums\KenyaCounty::values() as $county)
                    <option value="{{ $county }}" {{ old('county') === $county ? 'selected' : '' }}>{{ $county }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('county')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-bold text-soil mb-2">{{ __('Password') }}</label>
            <input id="password" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-bold text-soil mb-2">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 text-sm" />
        </div>

        <div class="pt-2">
            <button class="btn-primary w-full justify-center text-lg py-3">
                {{ __('Register') }}
            </button>
        </div>

        <p class="text-center text-sm text-bark mt-6">
            Already registered? 
            <a href="{{ route('login') }}" class="font-bold text-primary hover:text-primary-dark transition-colors">Log in here</a>
        </p>
    </form>
</x-guest-layout>
