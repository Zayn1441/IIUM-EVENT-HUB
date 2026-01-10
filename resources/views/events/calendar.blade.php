<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="calendar()">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Event Calendar</h1>
                <p class="text-muted-foreground mt-1">View and manage your upcoming events</p>
            </div>


        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Calendar Grid (Main) -->
            <div class="lg:col-span-3">
                <x-card class="h-full">
                    <x-card.content class="p-6">
                        <!-- Calendar Header -->
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-xl font-bold text-foreground" x-text="monthYear"></h2>
                            <div class="flex items-center gap-2">
                                <button @click="prevMonth"
                                    class="p-2 hover:bg-muted rounded-full transition-colors text-muted-foreground hover:text-foreground">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <button @click="today"
                                    class="px-3 py-1 text-sm font-medium hover:bg-muted rounded-md transition-colors border border-border">
                                    Today
                                </button>
                                <button @click="nextMonth"
                                    class="p-2 hover:bg-muted rounded-full transition-colors text-muted-foreground hover:text-foreground">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Days Header -->
                        <div class="grid grid-cols-7 mb-4">
                            <template x-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']">
                                <div class="text-center text-sm font-medium text-muted-foreground py-2" x-text="day">
                                </div>
                            </template>
                        </div>

                        <!-- Days Grid -->
                        <div
                            class="grid grid-cols-7 gap-px bg-muted/20 border border-border rounded-lg overflow-hidden">
                            <template x-for="(date, index) in days" :key="index">
                                <div @click="selectDate(date)" :class="{
                                        'bg-background min-h-[120px] p-2 cursor-pointer hover:bg-muted/50 transition-colors border-r border-b border-border': true,
                                        'bg-muted/5 text-muted-foreground': !date.isCurrentMonth,
                                        'ring-2 ring-inset ring-primary z-10': isSameDate(date.date, selectedDate)
                                     }" class="relative flex flex-col group">

                                    <span :class="{
                                        'flex items-center justify-center w-7 h-7 rounded-full text-sm font-medium mb-1': true,
                                        'bg-primary text-primary-foreground': isToday(date.date),
                                        'text-foreground': !isToday(date.date) && date.isCurrentMonth,
                                        'text-muted-foreground': !date.isCurrentMonth
                                    }" x-text="date.day"></span>

                                    <!-- Events Dots/Badges -->
                                    <div class="flex flex-col gap-1 mt-1 overflow-y-auto max-h-[80px] custom-scrollbar">
                                        <template x-for="event in getEventsForDate(date.date)">
                                            <a :href="'/events/' + event.id"
                                                class="text-[10px] px-1.5 py-0.5 rounded truncate text-white hover:opacity-90 transition-opacity block"
                                                :class="{
                                                  'bg-blue-600': event.category === 'Academic',
                                                  'bg-green-600': event.category === 'Sports',
                                                  'bg-purple-600': event.category === 'Cultural',
                                                  'bg-amber-600': event.category === 'Religious',
                                                  'bg-pink-600': event.category === 'Social',
                                                  'bg-cyan-600': event.category === 'Workshop',
                                                  'bg-gray-600': !['Academic', 'Sports', 'Cultural', 'Religious', 'Social', 'Workshop'].includes(event.category)
                                               }" x-text="event.title">
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </x-card.content>
                </x-card>
            </div>

            <!-- Sidebar (Day View) -->
            <div class="lg:col-span-1 space-y-6">
                <x-card>
                    <x-card.content class="p-6">
                        <h3 class="font-bold text-foreground mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span x-text="formattedSelectedDate"></span>
                        </h3>

                        <template x-if="selectedDateEvents.length > 0">
                            <div class="space-y-4">
                                <template x-for="event in selectedDateEvents">
                                    <div @click="window.location.href = '/events/' + event.id"
                                        class="group relative bg-muted/30 p-3 rounded-lg border border-border hover:border-primary/50 transition-all cursor-pointer">
                                        <div class="absolute left-0 top-3 bottom-3 w-1 bg-primary rounded-r-full"></div>
                                        <div class="pl-3">
                                            <a :href="'/events/' + event.id"
                                                class="font-semibold text-sm text-foreground hover:text-primary transition-colors block line-clamp-1"
                                                x-text="event.title"></a>
                                            <div class="text-xs text-muted-foreground mt-1 flex items-center gap-2">
                                                <span x-text="formatTime(event.time)"></span>
                                                <span>•</span>
                                                <span x-text="event.location"></span>
                                            </div>
                                            <div class="mt-2 flex gap-1">
                                                <span
                                                    class="text-[10px] px-1.5 py-0.5 rounded-full bg-background border border-border"
                                                    x-text="event.category"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="selectedDateEvents.length === 0">
                            <div class="text-center py-8">
                                <div
                                    class="w-12 h-12 bg-muted rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-muted-foreground" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-foreground">No events</p>
                                <p class="text-xs text-muted-foreground mt-1">No events scheduled for this day.</p>
                            </div>
                        </template>

                    </x-card.content>
                </x-card>

                <!-- Month Summary -->
                <x-card>
                    <x-card.content class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-sm text-muted-foreground uppercase tracking-wider">
                                Month Summary
                            </h4>
                            <div class="flex items-center gap-1">
                                <button @click="prevMonth"
                                    class="p-1 hover:bg-muted rounded-full text-muted-foreground hover:text-foreground">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <button @click="nextMonth"
                                    class="p-1 hover:bg-muted rounded-full text-muted-foreground hover:text-foreground">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <!-- Stats -->
                            <div
                                class="flex justify-between items-center bg-muted/30 p-3 rounded-lg border border-border">
                                <span class="text-sm font-medium text-foreground" x-text="monthYear"></span>
                                <span class="font-bold text-primary bg-primary/10 px-2 py-0.5 rounded text-sm"
                                    x-text="currentMonthEvents.length + ' Events'"></span>
                            </div>

                            <!-- Upcoming Events List -->
                            <div>
                                <h5 class="text-xs font-semibold text-muted-foreground mb-3">Upcoming this month</h5>
                                <div class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar">
                                    <template
                                        x-if="currentMonthEvents.filter(e => new Date(e.date) >= new Date().setHours(0,0,0,0)).length === 0">
                                        <p class="text-xs text-muted-foreground text-center py-2">No upcoming events</p>
                                    </template>

                                    <template
                                        x-for="event in currentMonthEvents.filter(e => new Date(e.date) >= new Date().setHours(0,0,0,0)).sort((a, b) => new Date(a.date) - new Date(b.date))">
                                        <a :href="'/events/' + event.id"
                                            class="flex items-start gap-3 p-2 rounded-md hover:bg-muted/50 transition-colors group">
                                            <div
                                                class="flex-shrink-0 w-10 text-center bg-muted rounded overflow-hidden border border-border">
                                                <div class="bg-primary/10 text-[10px] font-bold text-primary py-0.5"
                                                    x-text="new Date(event.date).toLocaleString('default', { month: 'short' })">
                                                </div>
                                                <div class="text-sm font-bold text-foreground py-0.5"
                                                    x-text="new Date(event.date).getDate()"></div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-medium text-foreground truncate group-hover:text-primary transition-colors"
                                                    x-text="event.title"></p>
                                                <p class="text-xs text-muted-foreground truncate"
                                                    x-text="formatTime(event.time) + ' • ' + event.location"></p>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </x-card.content>
                </x-card>


            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('calendar', () => ({
                    currentDate: new Date(),
                    selectedDate: new Date(),
                    view: 'month',
                    events: @json($events),

                    init() {
                        // normalize dates
                        this.events = this.events.map(e => ({
                            ...e,
                            dateObject: new Date(e.date)
                        }));
                    },

                    get monthYear() {
                        return this.currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                    },

                    get days() {
                        const year = this.currentDate.getFullYear();
                        const month = this.currentDate.getMonth();

                        const firstDay = new Date(year, month, 1);
                        const lastDay = new Date(year, month + 1, 0);

                        const startingDayOfWeek = firstDay.getDay(); // 0-6
                        const totalDays = lastDay.getDate();

                        const daysArray = [];

                        // Previous month days
                        const prevMonthLastDay = new Date(year, month, 0).getDate();
                        for (let i = startingDayOfWeek - 1; i >= 0; i--) {
                            daysArray.push({
                                day: prevMonthLastDay - i,
                                date: new Date(year, month - 1, prevMonthLastDay - i),
                                isCurrentMonth: false
                            });
                        }

                        // Current month days
                        for (let i = 1; i <= totalDays; i++) {
                            daysArray.push({
                                day: i,
                                date: new Date(year, month, i),
                                isCurrentMonth: true
                            });
                        }

                        // Next month days to fill grid (42 cells total for 6 rows)
                        const remainingCells = 42 - daysArray.length;
                        for (let i = 1; i <= remainingCells; i++) {
                            daysArray.push({
                                day: i,
                                date: new Date(year, month + 1, i),
                                isCurrentMonth: false
                            });
                        }

                        return daysArray;
                    },

                    get selectedDateEvents() {
                        return this.getEventsForDate(this.selectedDate);
                    },

                    get currentMonthEvents() {
                        return this.events.filter(e => {
                            const d = new Date(e.date);
                            return d.getMonth() === this.currentDate.getMonth() &&
                                d.getFullYear() === this.currentDate.getFullYear();
                        });
                    },

                    get formattedSelectedDate() {
                        return this.selectedDate.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' });
                    },

                    prevMonth() {
                        this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1, 1);
                    },

                    nextMonth() {
                        this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 1);
                    },

                    today() {
                        this.currentDate = new Date();
                        this.selectedDate = new Date();
                    },

                    selectDate(dateObj) {
                        this.selectedDate = dateObj.date;
                        // Auto switch month if clicked on prev/next month day
                        if (dateObj.date.getMonth() !== this.currentDate.getMonth()) {
                            this.currentDate = new Date(dateObj.date.getFullYear(), dateObj.date.getMonth(), 1);
                        }
                    },

                    getEventsForDate(date) {
                        return this.events.filter(e => this.isSameDate(new Date(e.date), date));
                    },

                    isSameDate(d1, d2) {
                        return d1.getFullYear() === d2.getFullYear() &&
                            d1.getMonth() === d2.getMonth() &&
                            d1.getDate() === d2.getDate();
                    },

                    isToday(date) {
                        return this.isSameDate(date, new Date());
                    },

                    formatTime(timeString) {
                        // Basic time parsing logic assuming format "HH:mm:ss" or similar
                        // If it's already human readable string from DB, just return it
                        if (!timeString) return '';
                        // Try to parse if it's HH:mm:ss
                        const parts = timeString.split(':');
                        if (parts.length >= 2) {
                            const hours = parseInt(parts[0]);
                            const minutes = parts[1];
                            const ampm = hours >= 12 ? 'PM' : 'AM';
                            const h = hours % 12 || 12;
                            return `${h}:${minutes} ${ampm}`;
                        }
                        return timeString;
                    }
                }));
            });
        </script>
    @endpush
</x-app-layout>