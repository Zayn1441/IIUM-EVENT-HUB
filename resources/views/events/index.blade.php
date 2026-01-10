<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-bold text-foreground">Discover Events</h1>
            <p class="text-muted-foreground mt-2 text-lg">
                Browse and register for events happening at IIUM
            </p>
        </div>

        <!-- Search and Filters -->
        <form method="GET" action="{{ route('events.index') }}" class="space-y-6">
            @if(request('starpoints'))
                <input type="hidden" name="starpoints" value="1">
            @endif

            <div class="flex flex-wrap items-center gap-4">
                <!-- Search -->
                <div class="relative w-full md:w-[300px]">
                    <button type="submit" class="absolute inset-y-0 left-0 flex items-center px-3 z-10 cursor-pointer text-muted-foreground hover:text-primary transition-colors bg-transparent border-0 focus:outline-none">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                    <x-input type="search" name="search" value="{{ request('search') }}"
                        placeholder="Search events..." class="pl-10 w-full" />
                </div>

                <!-- Kulliyyah Filter (Multi-select Dropdown) -->
                <div class="relative" x-data="{ open: false, count: {{ count((array)request('organizer', [])) }} }">
                    <button type="button" @click="open = !open"
                        class="flex items-center justify-between w-[200px] h-10 px-3 py-2 text-sm bg-background border border-input rounded-md ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        <span x-text="count > 0 ? count + ' selected' : 'Select a Kulliyyah'"></span>
                        <svg class="h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" @click.outside="open = false" style="display: none;"
                        class="absolute right-0 z-50 w-56 mt-2 origin-top-right bg-white border rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <div class="p-2 space-y-1 max-h-60 overflow-y-auto">
                            @foreach(['AHAS KIRKHS', 'AIKOL', 'KAED', 'KENMS', 'KOED', 'KOE', 'KICT'] as $kulliyyah)
                                <label class="flex items-center px-2 py-2 rounded-md hover:bg-muted cursor-pointer">
                                    <input type="checkbox" name="organizer[]" value="{{ $kulliyyah }}"
                                        class="h-4 w-4 text-primary border-input rounded focus:ring-primary"
                                        {{ in_array($kulliyyah, (array)request('organizer', [])) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm">{{ $kulliyyah }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="p-2 border-t bg-gray-50">
                            <x-button type="submit" size="sm" class="w-full" @click="open = false">
                                Apply Filters
                            </x-button>
                        </div>
                    </div>
                </div>

                <!-- Sort Filter -->
                <div class="relative w-48">
                    <select name="sort" onchange="this.form.submit()"
                        class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Upcoming (Default)</option>
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest Created</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest Created</option>
                        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Date: Future to Past</option>
                        <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Date: Past to Future</option>
                         <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Title (A-Z)</option>
                        <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Title (Z-A)</option>
                    </select>
                </div>
            </div>

            <!-- Category Filters -->
            <div>
                <p class="text-sm font-medium text-muted-foreground mb-2">Categories:</p>
                <div class="flex flex-wrap gap-2">
                    <!-- 'All' button clears category -->
                    <a href="{{ route('events.index', array_merge(request()->except('category'), ['category' => null])) }}"
                        class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium transition-all shadow-sm border {{ !request('category') ? 'bg-blue-600 text-white border-blue-600 ring-2 ring-blue-600 ring-offset-2' : 'bg-background hover:bg-muted text-foreground border-input' }}">
                        All
                    </a>
                    @foreach(['Academic', 'Cultural', 'Sports', 'Religious', 'Social', 'Workshop', 'Market', 'Community'] as $cat)
                        @php
                            $isSelected = in_array($cat, (array)request('category', []));
                        @endphp
                        <label class="cursor-pointer">
                            <input type="checkbox" name="category[]" value="{{ $cat }}" class="hidden"
                                {{ $isSelected ? 'checked' : '' }}
                                onchange="this.form.submit()">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium transition-all shadow-sm border {{ $isSelected ? 'bg-blue-600 text-white border-blue-600 ring-2 ring-blue-600 ring-offset-2' : 'bg-background hover:bg-muted text-foreground border-input' }}">
                                {{ $cat }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Tag Filters -->
            <div>
                <p class="text-sm font-medium text-muted-foreground mb-2">Tags:</p>
                <div class="flex flex-wrap gap-2">
                    <!-- 'All' button clears tags -->
                    <a href="{{ route('events.index', array_merge(request()->except('tags'), ['tags' => null])) }}"
                        class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium transition-all shadow-sm border {{ !request('tags') ? 'bg-blue-600 text-white border-blue-600 ring-2 ring-blue-600 ring-offset-2' : 'bg-background hover:bg-muted text-foreground border-input' }}">
                        All
                    </a>
                    @foreach(['free food', 'talks', 'spiritual', 'health', 'exhibition', 'sport', 'paid', 'free'] as $tag)
                        @php
                            $isSelected = in_array($tag, (array)request('tags', []));
                        @endphp
                        <label class="cursor-pointer">
                            <input type="checkbox" name="tags[]" value="{{ $tag }}" class="hidden"
                                {{ $isSelected ? 'checked' : '' }}
                                onchange="this.form.submit()">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium transition-all shadow-sm border {{ $isSelected ? 'bg-blue-600 text-white border-blue-600 ring-2 ring-blue-600 ring-offset-2' : 'bg-background hover:bg-muted text-foreground border-input' }}">
                                {{ ucfirst($tag) }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        </form>

        <!-- Results Count -->
        <div class="text-muted-foreground">
            Showing {{ $events->count() }} events
        </div>

        @if($events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($events as $event)
                    <x-event-card :event="$event" />
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-muted mb-4">
                    <svg class="w-6 h-6 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-foreground">No events found</h3>
                <p class="text-muted-foreground mt-1">Try adjusting your search or filters.</p>
            </div>
        @endif
    </div>
</x-app-layout>