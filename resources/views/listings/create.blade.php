<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Post a Listing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Commodity --}}
                    <div>
                        <x-input-label for="commodity_id" :value="__('Commodity')" />
                        <x-select-input id="commodity_id" name="commodity_id" class="block mt-1 w-full" required>
                            <option value="">-- Select Commodity --</option>
                            @foreach ($commodities as $commodity)
                                <option value="{{ $commodity->id }}" {{ old('commodity_id') == $commodity->id ? 'selected' : '' }}>
                                    {{ $commodity->name }} (per {{ $commodity->unit->value }})
                                </option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('commodity_id')" class="mt-2" />
                    </div>

                    {{-- Title --}}
                    <div class="mt-4">
                        <x-input-label for="title" :value="__('Listing Title')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    {{-- Description --}}
                    <div class="mt-4">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description"
                            class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            rows="4">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    {{-- Price --}}
                    <div class="mt-4">
                        <x-input-label for="price_per_unit" :value="__('Price per Unit (KES)')" />
                        <x-text-input id="price_per_unit" class="block mt-1 w-full" type="number" name="price_per_unit" :value="old('price_per_unit')" min="1" step="0.01" required />
                        <x-input-error :messages="$errors->get('price_per_unit')" class="mt-2" />
                    </div>

                    {{-- Quantity & Minimum Order --}}
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="quantity_available" :value="__('Quantity Available')" />
                            <x-text-input id="quantity_available" class="block mt-1 w-full" type="number" name="quantity_available" :value="old('quantity_available')" min="1" required />
                            <x-input-error :messages="$errors->get('quantity_available')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="minimum_order_quantity" :value="__('Minimum Order Quantity')" />
                            <x-text-input id="minimum_order_quantity" class="block mt-1 w-full" type="number" name="minimum_order_quantity" :value="old('minimum_order_quantity')" min="1" required />
                            <x-input-error :messages="$errors->get('minimum_order_quantity')" class="mt-2" />
                        </div>
                    </div>

                    {{-- County & Town --}}
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="county" :value="__('County')" />
                            <x-select-input id="county" name="county" class="block mt-1 w-full" required>
                                <option value="">-- Select County --</option>
                                @foreach ($counties as $county)
                                    <option value="{{ $county->value }}" {{ old('county') === $county->value ? 'selected' : '' }}>
                                        {{ $county->value }}
                                    </option>
                                @endforeach
                            </x-select-input>
                            <x-input-error :messages="$errors->get('county')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="town" :value="__('Town (optional)')" />
                            <x-text-input id="town" class="block mt-1 w-full" type="text" name="town" :value="old('town')" />
                            <x-input-error :messages="$errors->get('town')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Images --}}
                    <div class="mt-4">
                        <x-input-label for="images" :value="__('Images (up to 5)')" />
                        <input id="images" type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp"
                            class="block mt-1 w-full text-sm text-gray-500 dark:text-gray-400
                                   file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                                   file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700
                                   hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300" />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">JPEG, PNG or WebP. Max 2MB each.</p>
                        <x-input-error :messages="$errors->get('images')" class="mt-2" />
                        <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('listings.index') }}"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md">
                            {{ __('Cancel') }}
                        </a>
                        <x-primary-button class="ms-4">
                            {{ __('Post Listing') }}
                        </x-primary-button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
