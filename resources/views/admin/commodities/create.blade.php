<x-admin-layout header="Add New Commodity">

    <div class="max-w-2xl mx-auto">
        <div class="bg-white border border-stone rounded-xl shadow-sm p-8">
            <form method="POST" action="{{ route('admin.commodities.store') }}" class="space-y-6">
                @csrf

                {{-- Name --}}
                <div>
                    <label class="block font-mono text-xs text-bark uppercase tracking-widest mb-2" for="name">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                        class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" 
                        required autofocus>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                {{-- Slug --}}
                <div>
                    <label class="block font-mono text-xs text-bark uppercase tracking-widest mb-2" for="slug">Slug (URL friendly)</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" 
                        class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-stone/5" 
                        placeholder="e.g. maize-white" required>
                    <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Icon --}}
                    <div>
                        <label class="block font-mono text-xs text-bark uppercase tracking-widest mb-2" for="icon">Icon (Emoji)</label>
                        <input type="text" name="icon" id="icon" value="{{ old('icon') }}" 
                            class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" 
                            placeholder="🌽" required>
                        <x-input-error :messages="$errors->get('icon')" class="mt-2" />
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="block font-mono text-xs text-bark uppercase tracking-widest mb-2" for="category">Category</label>
                        <select name="category" id="category" 
                            class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" 
                            required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->value }}" {{ old('category') == $category->value ? 'selected' : '' }}>
                                    {{ ucfirst($category->value) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category')" class="mt-2" />
                    </div>
                </div>

                {{-- Unit --}}
                <div>
                    <label class="block font-mono text-xs text-bark uppercase tracking-widest mb-2" for="unit">Standard Unit</label>
                    <select name="unit" id="unit" 
                        class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" 
                        required>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->value }}" {{ old('unit') == $unit->value ? 'selected' : '' }}>
                                {{ ucfirst($unit->value) }}
                            </label>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                </div>

                {{-- Description --}}
                <div>
                    <label class="block font-mono text-xs text-bark uppercase tracking-widest mb-2" for="description">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="pt-4 flex items-center justify-end gap-4">
                    <a href="{{ route('admin.commodities') }}" class="text-sm font-bold text-bark hover:text-soil transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        Create Commodity
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Simple slug generator
        document.getElementById('name').addEventListener('input', function(e) {
            const slugInput = document.getElementById('slug');
            if (slugInput.value === '' || slugInput.dataset.auto === 'true') {
                slugInput.value = e.target.value
                    .toLowerCase()
                    .replace(/[^\w ]+/g, '')
                    .replace(/ +/g, '-');
                slugInput.dataset.auto = 'true';
            }
        });
        document.getElementById('slug').addEventListener('input', function() {
            this.dataset.auto = 'false';
        });
    </script>

</x-admin-layout>
