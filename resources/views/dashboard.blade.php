<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Welcome Section -->
        <div>
            <h1 class="text-3xl font-bold text-foreground tracking-tight">Welcome to IIUM Event Hub</h1>
            <p class="text-muted-foreground mt-2 text-lg">
                Discover and register for upcoming events at International Islamic University Malaysia
            </p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-card class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Total Events</p>
                        <p class="text-3xl font-bold text-foreground mt-1">{{ $stats['total_events'] }}</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-lg flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </x-card>

            <x-card class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Upcoming Events</p>
                        <p class="text-3xl font-bold text-foreground mt-1">{{ $stats['upcoming_events'] }}</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-green-50 dark:bg-green-900/20 rounded-lg flex items-center justify-center text-green-600 dark:text-green-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Quick Actions -->
        <div>
            <h2 class="text-2xl font-bold text-foreground mb-6">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('events.calendar') }}" class="group block">
                    <x-card class="p-6 h-full hover:shadow-md transition-all border-l-4 border-l-blue-500">
                        <div class="flex items-start space-x-4">
                            <div
                                class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-lg group-hover:bg-blue-200 dark:group-hover:bg-blue-900/30 transition-colors">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-foreground text-lg mb-1">View Calendar</h3>
                                <p class="text-muted-foreground">See all events in calendar view</p>
                            </div>
                        </div>
                    </x-card>
                </a>

                <a href="{{ route('events.index') }}" class="group block">
                    <x-card class="p-6 h-full hover:shadow-md transition-all border-l-4 border-l-purple-500">
                        <div class="flex items-start space-x-4">
                            <div
                                class="p-3 bg-purple-100 dark:bg-purple-900/20 rounded-lg group-hover:bg-purple-200 dark:group-hover:bg-purple-900/30 transition-colors">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-foreground text-lg mb-1">Browse All</h3>
                                <p class="text-muted-foreground">Explore all available events</p>
                            </div>
                        </div>
                    </x-card>
                </a>
            </div>
        </div>

        <!-- Upcoming Events Section -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-foreground">Upcoming Events</h2>
                <a href="{{ route('events.index') }}"
                    class="text-sm text-primary hover:text-primary/80 font-medium hover:underline">See all events</a>
            </div>

            @if($upcomingEvents->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($upcomingEvents as $event)
                        <x-event-card :event="$event" />
                    @endforeach
                </div>
            @else
                <x-card class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-muted mb-4">
                        <svg class="w-6 h-6 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-foreground">No upcoming events</h3>
                    <p class="text-muted-foreground mt-1">Check back later for new events.</p>
                </x-card>
            @endif
        </div>


    </div>
</x-app-layout>