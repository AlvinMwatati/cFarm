<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Commodity') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('commodities.store') }}">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Category --}}
                    <div class="mt-4">
                        <x-input-label for="category" :value="__('Category')" />
                        <x-select-input id="category" name="category" class="block mt-1 w-full" required>
                            <option value="">-- Select Category --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->value }}" {{ old('category') === $category->value ? 'selected' : '' }}>
                                    {{ $category->label() }}
                                </option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('category')" class="mt-2" />
                    </div>

                    {{-- Unit --}}
                    <div class="mt-4">
                        <x-input-label for="unit" :value="__('Unit of Measure')" />
                        <x-select-input id="unit" name="unit" class="block mt-1 w-full" required>
                            <option value="">-- Select Unit --</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->value }}" {{ old('unit') === $unit->value ? 'selected' : '' }}>
                                    {{ $unit->label() }}
                                </option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                    </div>

                    {{-- Description --}}
                    <div class="mt-4">
                        <x-input-label for="description" :value="__('Description (optional)')" />
                        <textarea id="description" name="description" rows="3"
                            class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('commodities.index') }}"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md">
                            {{ __('Cancel') }}
                        </a>
                        <x-primary-button class="ms-4">
                            {{ __('Add Commodity') }}
                        </x-primary-button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
