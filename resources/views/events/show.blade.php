<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <x-button variant="ghost" as="a" href="{{ route('events.index') }}"
                class="pl-0 hover:bg-transparent hover:text-foreground gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Events
            </x-button>
        </div>

        <!-- Hero Section -->
        <div class="relative h-64 md:h-96 rounded-2xl overflow-hidden mb-8 group shadow-sm ring-1 ring-border/5">
            <x-event-image src="{{ $event->image_path ? asset($event->image_path) : '' }}" alt="{{ $event->title }}"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />

            <!-- Category Badge -->
            <div class="absolute top-4 left-4">
                @php
                    $badgeClass = match ($event->category) {
                        'Academic' => 'bg-blue-100 text-blue-800 border-blue-200 hover:bg-blue-100',
                        'Cultural' => 'bg-purple-100 text-purple-800 border-purple-200 hover:bg-purple-100',
                        'Sports' => 'bg-green-100 text-green-800 border-green-200 hover:bg-green-100',
                        'Religious' => 'bg-amber-100 text-amber-800 border-amber-200 hover:bg-amber-100',
                        'Social' => 'bg-pink-100 text-pink-800 border-pink-200 hover:bg-pink-100',
                        'Workshop' => 'bg-cyan-100 text-cyan-800 border-cyan-200 hover:bg-cyan-100',
                        'Market' => 'bg-emerald-100 text-emerald-800 border-emerald-200 hover:bg-emerald-100',
                        'Community' => 'bg-orange-100 text-orange-800 border-orange-200 hover:bg-orange-100',
                        default => 'bg-gray-100 text-gray-800 border-gray-200'
                    };
                @endphp
                <x-badge class="{{ $badgeClass }}">
                    {{ $event->category }}
                </x-badge>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-8">
                <div>
                    <h1 class="text-3xl font-bold text-foreground mb-4">{{ $event->title }}</h1>
                </div>

                <!-- Event Information (Grid of Truth) -->
                <x-card>
                    <x-card.header>
                        <x-card.title>Event Information</x-card.title>
                    </x-card.header>
                    <x-card.content>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Date -->
                            <div class="p-4 bg-muted/50 rounded-lg">
                                <div class="flex items-center gap-3 mb-2">
                                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p class="text-muted-foreground font-medium">Date</p>
                                </div>
                                <p class="font-semibold text-foreground">{{ $event->date->format('d M Y') }}</p>
                            </div>

                            <!-- Time -->
                            <div class="p-4 bg-muted/50 rounded-lg">
                                <div class="flex items-center gap-3 mb-2">
                                    <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-muted-foreground font-medium">Time</p>
                                </div>
                                <p class="font-semibold text-foreground">
                                    {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                                </p>
                            </div>

                            <!-- Venue -->
                            <div class="p-4 bg-muted/50 rounded-lg">
                                <div class="flex items-center gap-3 mb-2">
                                    <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <p class="text-muted-foreground font-medium">Venue</p>
                                </div>
                                <p class="font-semibold text-foreground">{{ $event->location }}</p>
                            </div>

                            <!-- Starpoints -->
                            <div class="p-4 bg-muted/50 rounded-lg">
                                <div class="flex items-center gap-3 mb-2">
                                    <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                        </path>
                                    </svg>
                                    <p class="text-muted-foreground font-medium">Starpoints</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($event->is_starpoints)
                                        <div
                                            class="flex items-center text-green-600 dark:text-green-400 font-semibold gap-1">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>Yes</span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-red-500 dark:text-red-400 font-semibold gap-1">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span>No</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </x-card.content>
                </x-card>



                <!-- About This Event -->
                <x-card class="p-6">
                    <h3 class="font-bold text-foreground mb-4">About This Event</h3>
                    <p class="text-muted-foreground text-sm leading-relaxed whitespace-pre-line break-words">
                        {!! preg_replace('~(https?://[^\s<]+)~', '<a href="$1" class="text-blue-600 hover:underline break-all" target="_blank">$1</a>', e(trim($event->description))) !!}
                    </p>
                </x-card>


            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-6">
                <!-- Friends Going (Mockup) -->


                <!-- Event Details Summary & Action -->
                <x-card class="p-6">
                    <x-card.title class="text-sm font-bold mb-6">Event Details</x-card.title>

                    <div class="space-y-5 mb-8">
                        <div class="flex items-start">
                            <div class="w-5 h-5 mt-0.5 text-muted-foreground mr-3">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground font-medium">Date</p>
                                <p class="text-sm font-semibold text-foreground">{{ $event->date->format('l, d F Y') }}
                                </p>
                            </div>
                        </div>
                        <!-- ... other details same as before ... -->
                        <div class="flex items-start">
                            <div class="w-5 h-5 mt-0.5 text-muted-foreground mr-3">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground font-medium">Time</p>
                                <p class="text-sm font-semibold text-foreground">
                                    {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-5 h-5 mt-0.5 text-muted-foreground mr-3">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground font-medium">Location</p>
                                <p class="text-sm font-semibold text-foreground">{{ $event->location }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-5 h-5 mt-0.5 text-muted-foreground mr-3">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground font-medium">Organizer</p>
                                <p class="text-sm font-semibold text-foreground">{{ $event->organizer }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-5 h-5 mt-0.5 text-muted-foreground mr-3">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a2.25 2.25 0 00.26-.26l4.854-4.853a2.25 2.25 0 00.33-2.607.75.75 0 00-.26-.26L11.159 3.66A2.25 2.25 0 009.568 3z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                </svg>
                            </div>
                            <div class="w-full">
                                <p class="text-xs text-muted-foreground font-medium">Tags</p>
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @forelse($event->tags as $tag)
                                        <x-badge variant="secondary" class="text-xs px-2 py-0.5">
                                            {{ $tag->name }}
                                        </x-badge>
                                    @empty
                                        <span class="text-sm text-muted-foreground">-</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3">
                        @if(auth()->id() === $event->user_id || (auth()->user() && auth()->user()->is_admin))
                            <x-button variant="outline" class="w-full gap-2" as="a"
                                href="{{ route('events.edit', $event) }}">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                                Edit Event
                            </x-button>
                        @endif

                        @php
                            $isPast = \Carbon\Carbon::parse($event->date->format('Y-m-d') . ' ' . $event->time)->isPast();
                        @endphp

                        @if($event->participation_link)
                            @if($isPast)
                                <div x-data>
                                    <x-button class="w-full opacity-75" size="lg" type="button"
                                        x-on:click="$dispatch('open-modal', 'event-passed-modal')">
                                        Join Event
                                    </x-button>
                                </div>
                            @else
                                <x-button class="w-full" size="lg" as="a" href="{{ $event->participation_link }}"
                                    target="_blank">
                                    Join Event
                                </x-button>
                            @endif
                        @else
                            @if($isPast)
                                <div x-data>
                                    <x-button class="w-full opacity-75" size="lg" type="button"
                                        x-on:click="$dispatch('open-modal', 'event-passed-modal')">
                                        Join Event
                                    </x-button>
                                </div>
                            @else
                                <div x-data>
                                    <x-button class="w-full" size="lg" type="button"
                                        x-on:click="$dispatch('open-modal', 'no-link-modal')">
                                        Join Event
                                    </x-button>
                                </div>
                            @endif
                        @endif



                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('events.save', $event) }}" class="flex-1">
                                @csrf
                                @php
                                    $isSaved = $event->isSavedBy(auth()->user());
                                @endphp
                                <x-button variant="{{ $isSaved ? 'default' : 'outline' }}" class="w-full gap-2">
                                    <svg class="w-4 h-4 {{ $isSaved ? 'fill-current' : 'fill-none' }}"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                    </svg>
                                    {{ $isSaved ? 'Saved' : 'Save' }}
                                </x-button>
                            </form>
                            <x-button variant="outline" class="flex-1 gap-2"
                                x-on:click="$dispatch('open-modal', 'share-modal')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z">
                                    </path>
                                </svg>
                                Share
                            </x-button>
                        </div>
                    </div>
                </x-card>

                <!-- Report Button -->
                <div class="mt-8">
                    <button x-data x-on:click="$dispatch('open-modal', 'report-event-modal')"
                        class="text-xs text-red-500 hover:text-red-700 hover:underline flex items-center gap-1 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Report this event
                    </button>
                </div>
            </div>
        </div>
    </div>

    <x-modal name="share-modal" focusable>
        <div class="text-center">
            <h2 class="text-lg font-medium text-gray-900">
                Function In Development
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                This function is currently in development.
            </p>
            <div class="mt-6 flex justify-center">
                <x-secondary-button x-on:click="$dispatch('close-modal', 'share-modal')">
                    Okay
                </x-secondary-button>
            </div>
        </div>
    </x-modal>

    <x-modal name="report-event-modal" focusable>
        <form method="POST" action="{{ route('events.report', $event) }}" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900 mb-4">
                Report Event
            </h2>

            <div class="mb-6">
                <x-input-label for="reason" value="Reason for reporting" class="mb-2" />
                <textarea name="reason" id="reason" rows="4"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    placeholder="Refers to false/dangerous link, inappropriate content, etc." required></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close-modal', 'report-event-modal')">
                    Cancel
                </x-secondary-button>

                <x-button class="bg-red-600 hover:bg-red-700 focus:bg-red-700 active:bg-red-800">
                    Submit Report
                </x-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>