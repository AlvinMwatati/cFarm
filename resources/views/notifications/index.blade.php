<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Notifications
            </h2>
            @if(auth()->user()->notifications->isNotEmpty())
                <form method="POST" action="{{ route('notifications.destroyAll') }}">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="text-sm text-red-600 dark:text-red-400 hover:underline">
                        Clear all
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if ($notifications->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                    <p class="text-gray-500 dark:text-gray-400">No notifications yet.</p>
                    <a href="{{ route('follows.index') }}"
                        class="mt-4 inline-block underline text-indigo-600 dark:text-indigo-400 text-sm">
                        Follow commodities to get alerts
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($notifications as $notification)
                        @php $data = $notification->data; @endphp
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 flex items-start justify-between
                            {{ $notification->read_at ? 'opacity-60' : 'border-l-4 border-indigo-500' }}">
                            <div class="flex items-start gap-3">
                                <span class="text-2xl mt-0.5">
                                    @if($data['type'] === 'price_drop') 📉
                                    @elseif($data['type'] === 'price_spike') 📈
                                    @elseif($data['type'] === 'new_listing') 🌾
                                    @elseif($data['type'] === 'weekly_summary') 📊
                                    @else 🔔
                                    @endif
                                </span>
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-gray-100 text-sm">
                                        {{ $data['message'] ?? 'Notification' }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-300 hover:text-red-400 text-xs ml-4">✕</button>
                            </form>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">{{ $notifications->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
