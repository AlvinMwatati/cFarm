<section>
    <header>
        <h2 class="font-display text-xl font-bold text-soil">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-bark">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-bold text-soil mb-2">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-soil mb-2">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-soil">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-primary hover:text-primary-dark rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-accent-dark">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <label for="phone" class="block text-sm font-bold text-soil mb-2">{{ __('Phone') }}</label>
            <input id="phone" name="phone" type="text" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" value="{{ old('phone', $user->phone) }}" required autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <label for="county_search" class="block text-sm font-bold text-soil mb-2">{{ __('County') }}</label>
            <select id="county_search" class="searchable-select w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" name="county" required>
                <option value="" disabled {{ old('county', $user->county) ? '' : 'selected' }}>
                    {{ __('Select your county') }}</option>
                @foreach (\App\Enums\KenyaCounty::values() as $county)
                    <option value="{{ $county }}"
                        {{ old('county', $user->county) === $county ? 'selected' : '' }}>{{ $county }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('county')" />
        </div>

        <div class="flex items-center gap-4">
            <button class="btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-bark font-bold">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
