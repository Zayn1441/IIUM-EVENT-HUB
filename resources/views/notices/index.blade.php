<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-foreground">Notices</h1>
            @if($notices->count() > 0)
                <form action="{{ route('notices.clear') }}" method="POST" onsubmit="return confirm('Clear all notices?');">
                    @csrf
                    @method('DELETE')
                    <x-button variant="destructive">Clear All</x-button>
                </form>
            @endif
        </div>

        <div class="space-y-4">
            @forelse($notices as $notice)
                <div
                    class="bg-white rounded-lg shadow border border-gray-200 p-4 {{ $notice->is_read ? 'opacity-75' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                @if($notice->type == 'admin_message')
                                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Admin
                                        Message</span>
                                @elseif($notice->type == 'system')
                                    <span
                                        class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">System</span>
                                @else
                                    <span
                                        class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Update</span>
                                @endif
                                <span class="text-xs text-gray-500">{{ $notice->created_at->diffForHumans() }}</span>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $notice->title }}</h3>
                            <p class="text-gray-600 whitespace-pre-line mb-3">{{ $notice->message }}</p>

                            @if($notice->action_url)
                                <a href="{{ $notice->action_url }}"
                                    class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                    View Event Details
                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                        @if(!$notice->is_read)
                            <div class="w-3 h-3 bg-blue-600 rounded-full flex-shrink-0 mt-1" title="Unread"></div>
                        @endif
                    </div>
                </div>
            @empty
                <x-card class="bg-muted/50 border-dashed">
                    <x-card.content
                        class="flex flex-col items-center justify-center py-12 text-center text-muted-foreground">
                        <svg class="h-12 w-12 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <p class="text-lg font-medium">No notices</p>
                        <p>You're all caught up!</p>
                    </x-card.content>
                </x-card>
            @endforelse

            <div class="mt-4">
                {{ $notices->links() }}
            </div>
        </div>
    </div>
</x-app-layout>