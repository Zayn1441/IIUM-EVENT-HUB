@props(['event'])

@php
    $registered = $event->registrations_count ?? 0;
    $capacity = $event->capacity;
    $percentage = $capacity > 0 ? ($registered / $capacity) * 100 : 0;
    $isFull = $capacity > 0 && $registered >= $capacity;
    $isAlmostFull = $capacity > 0 && !$isFull && $percentage >= 90;

    $eventDate = \Carbon\Carbon::parse($event->date->format('Y-m-d') . ' ' . $event->time);
    $isPast = now()->greaterThan($eventDate);
    $isFuture = now()->lessThan($eventDate);
    $diffInDays = now()->diffInDays($eventDate, false);
    $isToday = now()->isSameDay($eventDate);
@endphp

<x-card class="flex flex-col h-full overflow-hidden hover:shadow-md transition-shadow group cursor-pointer" x-data
    @click="if(!$event.target.closest('a, button, form, input')) window.location.href = '{{ route('events.show', $event) }}'">
    <!-- Image Placeholder -->
    <div class="h-40 bg-muted w-full relative overflow-hidden">
        @if($event->image_path)
            <img src="{{ asset($event->image_path) }}" alt="{{ $event->title }}" loading="lazy"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
        @else
            <!-- Dynamic color based on category -->
            @php
                $bgClass = match ($event->category) {
                    'Sports' => 'bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400',
                    'Cultural' => 'bg-purple-100 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400',
                    'Academic' => 'bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400',
                    'Religious' => 'bg-amber-100 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400',
                    'Social' => 'bg-pink-100 dark:bg-pink-900/20 text-pink-600 dark:text-pink-400',
                    'Workshop' => 'bg-cyan-100 dark:bg-cyan-900/20 text-cyan-600 dark:text-cyan-400',
                    'Market' => 'bg-emerald-100 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400',
                    'Community' => 'bg-orange-100 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400',
                    default => 'bg-muted text-muted-foreground'
                };
            @endphp
            <div class="w-full h-full flex items-center justify-center {{ $bgClass }}">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
        @endif

        <div class="absolute top-3 right-3">
            <x-badge variant="secondary" class="bg-background/90 backdrop-blur shadow-sm">
                {{ $event->category }}
            </x-badge>
        </div>

        @if(now()->greaterThan(\Carbon\Carbon::parse($event->date->format('Y-m-d') . ' ' . $event->time)))
            <div class="absolute top-3 left-3">
                <x-badge variant="secondary" class="bg-background/90 backdrop-blur shadow-sm text-foreground">
                    Past Event
                </x-badge>
            </div>
        @endif

        @if($isFuture)
            <div class="absolute top-3 left-3">
                <x-badge variant="secondary" class="bg-background/90 backdrop-blur shadow-sm text-foreground">
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

    <div class="p-5 flex-1 flex flex-col">
        <h4 class="font-bold text-foreground mb-2 truncate text-lg">{{ $event->title }}</h4>
        <p class="text-sm text-muted-foreground mb-4 line-clamp-2 flex-1">{{ $event->description }}</p>

        <div class="space-y-2 text-sm text-muted-foreground mb-6">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                {{ $event->date->format('D, d M Y') }}
            </div>
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
            </div>
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ $event->location }}
            </div>

            @if($event->is_starpoints)
                <div class="flex items-center text-yellow-600 dark:text-yellow-400 font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                        </path>
                    </svg>
                    Starpoints Available
                </div>
            @endif
        </div>

        <div class="flex items-center justify-center mt-auto gap-3">
            @if(isset($actions))
                {{ $actions }}
            @elseif(auth()->user() && auth()->user()->is_admin)
                <x-button as="a" href="{{ route('events.show', $event) }}" variant="outline" size="sm" class="flex-1">
                    View Details
                </x-button>
                <x-button as="a" href="{{ route('events.edit', $event) }}" variant="secondary" size="sm" class="flex-1">
                    Edit
                </x-button>
                <form action="{{ route('events.destroy', $event) }}" method="POST" class="flex-1"
                    onsubmit="return confirm('Are you sure you want to delete this event?');">
                    @csrf
                    @method('DELETE')
                    <x-button variant="destructive" size="sm" class="w-full">
                        Delete
                    </x-button>
                </form>
            @else
                <x-button as="a" href="{{ route('events.show', $event) }}" variant="outline" size="sm" class="flex-1">
                    View Details
                </x-button>
                @if($event->participation_link)
                    @if($isPast)
                        <x-button size="sm" class="flex-1 w-full opacity-75" type="button"
                            x-on:click.stop="$dispatch('open-modal', 'event-passed-modal')">
                            Join Event
                        </x-button>
                    @else
                        <x-button size="sm" class="flex-1 w-full" as="a" href="{{ $event->participation_link }}" target="_blank"
                            x-on:click.stop="void(0)">
                            Join Event
                        </x-button>
                    @endif
                @else
                    @if($isPast)
                        <x-button size="sm" class="flex-1 w-full opacity-75" type="button"
                            x-on:click.stop="$dispatch('open-modal', 'event-passed-modal')">
                            Join Event
                        </x-button>
                    @else
                        <x-button size="sm" class="flex-1 w-full" type="button"
                            x-on:click.stop="$dispatch('open-modal', 'no-link-modal')">
                            Join Event
                        </x-button>
                    @endif
                @endif
            @endif
        </div>
    </div>
</x-card>