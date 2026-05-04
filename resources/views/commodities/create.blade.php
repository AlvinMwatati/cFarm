@section('title', 'Add Commodity — cFarm')

<x-app-layout>
    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Back Link --}}
            <div>
                <a href="{{ route('commodities.index') }}" class="text-primary font-bold hover:underline text-sm">
                    ← Back to Directory
                </a>
            </div>

            {{-- Header --}}
            <div class="border-b border-stone pb-6">
                <h1 class="font-display text-3xl font-bold text-soil">Add Commodity</h1>
                <p class="text-bark mt-1">Add a new commodity to the marketplace directory.</p>
            </div>

            <div class="bg-white border border-stone rounded-[12px] shadow-sm p-8">
                <form method="POST" action="{{ route('commodities.store') }}" class="space-y-6">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-bold text-soil mb-2">Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" 
                               class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" 
                               placeholder="e.g. White Maize" required autofocus>
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600 text-sm" />
                    </div>

                    {{-- Category & Unit --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="category" class="block text-sm font-bold text-soil mb-2">Category</label>
                            <select id="category" name="category" 
                                    class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" required>
                                <option value="" disabled selected>Select category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->value }}" {{ old('category') === $category->value ? 'selected' : '' }}>
                                        {{ $category->label() }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2 text-red-600 text-sm" />
                        </div>
                        <div>
                            <label for="unit" class="block text-sm font-bold text-soil mb-2">Unit of Measure</label>
                            <select id="unit" name="unit" 
                                    class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" required>
                                <option value="" disabled selected>Select unit</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->value }}" {{ old('unit') === $unit->value ? 'selected' : '' }}>
                                        {{ $unit->label() }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('unit')" class="mt-2 text-red-600 text-sm" />
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-bold text-soil mb-2">Description <span class="text-bark font-normal">(optional)</span></label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white"
                                  placeholder="Brief description of this commodity...">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2 text-red-600 text-sm" />
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-stone/30">
                        <a href="{{ route('commodities.index') }}" class="text-sm font-bold text-bark hover:text-soil transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            Add Commodity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
