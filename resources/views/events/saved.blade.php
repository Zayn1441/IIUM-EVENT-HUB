<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6" 
        x-data="{ unsaveAction: '' }" 
        @set-unsave-action.window="unsaveAction = $event.detail">
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-bold text-foreground">Saved Events</h1>
            <p class="text-muted-foreground mt-2 text-lg">
                Events you've bookmarked for later
            </p>
        </div>

        @php
            $totalSaved = $savedRegistrations->count();
            $upcomingCount = $savedRegistrations->filter(fn($reg) => $reg->event && \Carbon\Carbon::parse($reg->event->date->format('Y-m-d') . ' ' . $reg->event->time)->isFuture())->count();
            $completedCount = $savedRegistrations->filter(fn($reg) => $reg->event && \Carbon\Carbon::parse($reg->event->date->format('Y-m-d') . ' ' . $reg->event->time)->isPast())->count();
        @endphp

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-card>
                <x-card.content class="p-4 flex items-center gap-3">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                        <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Total Saved</p>
                        <p class="text-2xl font-bold text-foreground">{{ $totalSaved }}</p>
                    </div>
                </x-card.content>
            </x-card>
            <x-card>
                <x-card.content class="p-4 flex items-center gap-3">
                    <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg">
                        <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Upcoming</p>
                        <p class="text-2xl font-bold text-foreground">{{ $upcomingCount }}</p>
                    </div>
                </x-card.content>
            </x-card>
            <x-card>
                <x-card.content class="p-4 flex items-center gap-3">
                    <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg">
                        <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Completed</p>
                        <p class="text-2xl font-bold text-foreground">{{ $completedCount }}</p>
                    </div>
                </x-card.content>
            </x-card>
        </div>

        <!-- Events List -->
        @if($savedRegistrations->isNotEmpty())
        <div class="grid grid-cols-1 gap-4">
            @foreach($savedRegistrations as $registration)
                    @php
                        $event = $registration->event;
                    @endphp
                        @php
                            $isPast = \Carbon\Carbon::parse($event->date->format('Y-m-d') . ' ' . $event->time)->isPast();
                            // Determine simple registration status logic
                            // In reality, this would check distinct registration types for the same event+user
                            // For now, let's just assume if it's in saved list, it's 'Saved' unless user also registered
                            // Checking if user is also registered logic would be heavy here without better backend query
                            // Let's stick to status of event itself
                            $statusBadge = $isPast ?
                                '<span class="inline-flex items-center rounded-full border border-gray-200 bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-800">Completed</span>' :
                                '<span class="inline-flex items-center rounded-full border border-blue-200 bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800">Upcoming</span>';
                        @endphp
                        <x-card class="hover:shadow-md transition-shadow cursor-pointer group"
                            x-data
                            @click="if(!$event.target.closest('a, button, form, input')) window.location.href = '{{ route('events.show', $event) }}'"
                        >
                            <x-card.content class="p-0">
                                <div class="flex flex-col md:flex-row gap-0">
                                    <!-- Image -->
                                    <div class="md:w-72 h-48 md:h-auto md:min-h-[14rem] flex-shrink-0 relative group self-stretch">
                                        <x-event-image src="{{ $event->image_path }}" alt="{{ $event->title }}"
                                            class="w-full h-full object-cover rounded-t-lg md:rounded-l-lg md:rounded-tr-none" />
                                        
                                        @if($isPast)
                                            <div class="absolute top-3 left-3">
                                                <x-badge variant="secondary" class="bg-white/90 backdrop-blur shadow-sm text-gray-900 border-gray-200">
                                                    Past Event
                                                </x-badge>
                                            </div>
                                        @endif
                                        
                                        @php
                                            $eventDate = \Carbon\Carbon::parse($event->date->format('Y-m-d') . ' ' . $event->time);
                                            $isFuture = now()->lessThan($eventDate);
                                            $diffInDays = now()->diffInDays($eventDate, false);
                                            $isToday = now()->isSameDay($eventDate);
                                        @endphp

                                        @if($isFuture)
                                            <div class="absolute top-3 left-3">
                                                <x-badge variant="secondary" class="bg-white/90 backdrop-blur shadow-sm text-gray-900 border-gray-200">
                                                    @if($isToday)
                                                        Today
                                                    @elseif($diffInDays < 1)
                                                        Tomorrow
                                                    @else
                                                        {{ ceil($diffInDays) }} days left
                                                    @endif
                                                </x-badge>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 p-6 flex flex-col justify-between">
                                        <div class="flex flex-col md:flex-row gap-4 h-full">
                                            <div class="flex-1 space-y-3">
                                                <!-- Title and Status -->
                                                <div class="flex items-start gap-3 flex-wrap">
                                                    <h3 class="text-xl font-bold flex-1">{{ $event->title }}</h3>
                                                    {!! $statusBadge !!}
                                                </div>

                                                <!-- Event Details -->
                                                <div class="space-y-2 text-sm">
                                                    <div class="flex items-center gap-2 text-muted-foreground">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                        <span>{{ $event->date->format('D, d M Y') }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-2 text-muted-foreground">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <span>{{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-2 text-muted-foreground">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        <span>{{ $event->location }}</span>
                                                    </div>
                                                </div>

                                                <!-- Tags -->
                                                @if($event->tags->isNotEmpty())
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($event->tags->take(3) as $tag)
                                                            <x-badge variant="secondary">
                                                                {{ $tag->name }}
                                                            </x-badge>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Actions -->
                                            <div class="flex flex-col gap-3 md:items-end w-full md:w-auto">
                                                <x-button variant="outline" size="sm" as="a"
                                                    href="{{ route('events.show', $event) }}" class="w-full md:w-auto">
                                                    View Details
                                                </x-button>

                                                <x-button variant="destructive" size="sm"
                                                    x-on:click.prevent="console.log('Dispatching unsave:', '{{ route('events.save', $event) }}'); $dispatch('set-unsave-action', '{{ route('events.save', $event) }}'); $dispatch('open-modal', 'confirm-unsave')"
                                                    class="w-full md:w-auto">
                                                    Unsave
                                                </x-button>

                                                <!-- Remind Me Toggle -->
                                                <form method="POST" action="{{ route('events.toggle-reminder', $event) }}">
                                                    @csrf
                                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                                        <div class="relative">
                                                            <input type="checkbox" class="sr-only peer"
                                                                onchange="this.form.submit()" {{ $registration->remind_me ? 'checked' : '' }}>
                                                            <div
                                                                class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600">
                                                            </div>
                                                        </div>
                                                        <span class="text-sm text-foreground">Remind Me</span>
                                                    </label>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </x-card.content>
                        </x-card>

                    @endforeach
                </div>
            @else
        <x-card>
            <x-card.content class="p-12 text-center">
                <div class="bg-muted p-4 rounded-full inline-block mb-4">
                    <svg class="h-12 w-12 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                </div>
                <h3 class="mb-2 text-xl font-semibold text-foreground">No saved events yet</h3>
                <p class="text-muted-foreground">
                    Start exploring and save events you're interested in
                </p>
                <x-button as="a" href="{{ route('events.index') }}" class="mt-4">
                    Browse Events
                </x-button>
            </x-card.content>
        </x-card>
        @endif
    <x-modal name="confirm-unsave" focusable>
        <form method="post" :action="unsaveAction" class="p-6">
            @csrf
            
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Remove from Saved Events?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Are you sure you want to remove this event from your saved list? You can always save it again later.') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close-modal', 'confirm-unsave')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ml-3">
                    {{ __('Remove Event') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</div>
</x-app-layout>