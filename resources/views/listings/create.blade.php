<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="mb-6 border-b border-stone pb-4">
                <h2 class="font-display text-3xl font-bold text-soil">Post a Listing</h2>
                <p class="text-bark mt-1">Create a new marketplace listing to sell your commodities.</p>
            </div>

            <div class="bg-white border border-stone shadow-sm rounded-[12px] p-8">
                <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- Commodity --}}
                    <div>
                        <label for="commodity_id" class="block text-sm font-bold text-soil mb-2">Commodity <span class="text-red-500">*</span></label>
                        <select id="commodity_id" name="commodity_id" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" required>
                            <option value="">-- Select Commodity --</option>
                            @foreach ($commodities as $commodity)
                                <option value="{{ $commodity->id }}" {{ old('commodity_id') == $commodity->id ? 'selected' : '' }}>
                                    {{ $commodity->name }} (per {{ $commodity->unit->value }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('commodity_id')" class="mt-2" />
                    </div>

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-bold text-soil mb-2">Listing Title <span class="text-red-500">*</span></label>
                        <input id="title" type="text" name="title" value="{{ old('title') }}" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" required placeholder="e.g., Premium Grade Maize">
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-bold text-soil mb-2">Description</label>
                        <textarea id="description" name="description" rows="4" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" placeholder="Provide more details about the quality, delivery options, etc.">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    {{-- Price --}}
                    <div>
                        <label for="price_per_unit" class="block text-sm font-bold text-soil mb-2">Price per Unit (KES) <span class="text-red-500">*</span></label>
                        <input id="price_per_unit" type="number" name="price_per_unit" value="{{ old('price_per_unit') }}" min="1" step="0.01" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" required placeholder="e.g., 3500">
                        <x-input-error :messages="$errors->get('price_per_unit')" class="mt-2" />
                    </div>

                    {{-- Quantity & Minimum Order --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="quantity_available" class="block text-sm font-bold text-soil mb-2">Quantity Available <span class="text-red-500">*</span></label>
                            <input id="quantity_available" type="number" name="quantity_available" value="{{ old('quantity_available') }}" min="1" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" required>
                            <x-input-error :messages="$errors->get('quantity_available')" class="mt-2" />
                        </div>
                        <div>
                            <label for="minimum_order_quantity" class="block text-sm font-bold text-soil mb-2">Minimum Order Quantity <span class="text-red-500">*</span></label>
                            <input id="minimum_order_quantity" type="number" name="minimum_order_quantity" value="{{ old('minimum_order_quantity') }}" min="1" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" required>
                            <x-input-error :messages="$errors->get('minimum_order_quantity')" class="mt-2" />
                        </div>
                    </div>

                    {{-- County & Town --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="county" class="block text-sm font-bold text-soil mb-2">County <span class="text-red-500">*</span></label>
                            <select id="county" name="county" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" required>
                                <option value="">-- Select County --</option>
                                @foreach ($counties as $county)
                                    <option value="{{ $county->value }}" {{ old('county') === $county->value ? 'selected' : '' }}>
                                        {{ $county->value }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('county')" class="mt-2" />
                        </div>
                        <div>
                            <label for="town" class="block text-sm font-bold text-soil mb-2">Town (Optional)</label>
                            <input id="town" type="text" name="town" value="{{ old('town') }}" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" placeholder="e.g., Kitale">
                            <x-input-error :messages="$errors->get('town')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Images --}}
                    <div>
                        <label for="images" class="block text-sm font-bold text-soil mb-2">Images (up to 5)</label>
                        <input id="images" type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp"
                            class="block w-full text-sm text-bark
                                   file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0
                                   file:text-sm file:font-bold file:bg-stone/50 file:text-soil
                                   hover:file:bg-stone transition-colors cursor-pointer border border-dashed border-stone rounded-lg p-2 bg-stone/10" />
                        <p class="mt-2 text-sm text-bark">Supported formats: JPEG, PNG or WebP. Max 2MB each.</p>
                        <x-input-error :messages="$errors->get('images')" class="mt-2" />
                        <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                    </div>

                    {{-- Submit --}}
                    <div class="pt-6 border-t border-stone flex flex-col sm:flex-row items-center justify-end gap-4 mt-8">
                        <a href="{{ route('listings.index') }}" class="btn-secondary w-full sm:w-auto text-center justify-center">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary w-full sm:w-auto justify-center">
                            Post Listing
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
