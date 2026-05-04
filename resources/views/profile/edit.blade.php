@section('title', 'Profile Settings — cFarm')

<x-app-layout>
    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Header --}}
            <div class="border-b border-stone pb-6">
                <h1 class="font-display text-3xl font-bold text-soil">Profile Settings</h1>
                <p class="text-bark mt-1">Manage your account information, password, and security.</p>
            </div>

            {{-- Tabs --}}
            <div x-data="{ tab: '{{ $errors->updatePassword->isNotEmpty() ? 'password' : ($errors->userDeletion->isNotEmpty() ? 'danger' : 'profile') }}' }">
                <div class="flex gap-1 border-b border-stone mb-8 overflow-x-auto no-scrollbar">
                    <button @click="tab = 'profile'" :class="tab === 'profile' ? 'border-b-2 border-primary text-primary' : 'text-bark hover:text-soil'" 
                            class="px-5 py-3 font-bold text-sm transition-colors whitespace-nowrap -mb-px">
                        👤 Profile Info
                    </button>
                    <button @click="tab = 'password'" :class="tab === 'password' ? 'border-b-2 border-primary text-primary' : 'text-bark hover:text-soil'" 
                            class="px-5 py-3 font-bold text-sm transition-colors whitespace-nowrap -mb-px">
                        🔒 Password
                    </button>
                    <button @click="tab = 'danger'" :class="tab === 'danger' ? 'border-b-2 border-red-500 text-red-600' : 'text-bark hover:text-soil'" 
                            class="px-5 py-3 font-bold text-sm transition-colors whitespace-nowrap -mb-px">
                        ⚠️ Danger Zone
                    </button>
                </div>

                {{-- Profile Tab --}}
                <div x-show="tab === 'profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="p-6 sm:p-8 bg-white border border-stone shadow-sm rounded-[12px]">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                {{-- Password Tab --}}
                <div x-show="tab === 'password'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                    <div class="p-6 sm:p-8 bg-white border border-stone shadow-sm rounded-[12px]">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                {{-- Danger Zone Tab --}}
                <div x-show="tab === 'danger'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                    <div class="p-6 sm:p-8 bg-white border-2 border-red-200 shadow-sm rounded-[12px] bg-red-50/30">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
