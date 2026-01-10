@props(['name' => 'date', 'value' => '', 'min' => '', 'max' => '', 'class' => ''])

<div x-data="{
        date: '{{ $value }}',
        showPicker: false,
        month: '',
        year: '',
        days: [],
        open() {
            this.showPicker = true;
            let d = this.date ? new Date(this.date) : new Date();
            this.month = d.getMonth();
            this.year = d.getFullYear();
            this.generateDays();
        },
        generateDays() {
            let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
            let startDay = new Date(this.year, this.month, 1).getDay();
            this.days = [];
            
            // Padding days
            for(let i = 0; i < startDay; i++) {
                this.days.push({ day: '', date: '' });
            }
            
            // Actual days
            for(let i = 1; i <= daysInMonth; i++) {
                let d = new Date(this.year, this.month, i);
                let dateStr = d.toISOString().split('T')[0];
                this.days.push({ 
                    day: i, 
                    date: dateStr,
                    isToday: dateStr === new Date().toISOString().split('T')[0],
                    isSelected: this.date === dateStr
                });
            }
        },
        prevMonth() {
            if (this.month === 0) {
                this.month = 11;
                this.year--;
            } else {
                this.month--;
            }
            this.generateDays();
        },
        nextMonth() {
            if (this.month === 11) {
                this.month = 0;
                this.year++;
            } else {
                this.month++;
            }
            this.generateDays();
        },
        selectDate(date) {
            this.date = date;
            this.showPicker = false;
        },
        monthName() {
            const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            return months[this.month] + ' ' + this.year;
        }
    }" class="relative {{ $class }}" @click.away="showPicker = false">
    <!-- Actual Input -->
    <input type="hidden" name="{{ $name }}" x-model="date">

    <!-- Trigger Button -->
    <button type="button" @click="open()"
        class="flex w-full items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm placeholder:text-gray-400 focus:outline-none focus:ring-1 focus:ring-black focus:border-black disabled:cursor-not-allowed disabled:opacity-50">
        <span x-text="date ? new Date(date).toLocaleDateString() : 'Select date'"></span>
        <svg class="h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
            <line x1="16" x2="16" y1="2" y2="6" />
            <line x1="8" x2="8" y1="2" y2="6" />
            <line x1="3" x2="21" y1="10" y2="10" />
        </svg>
    </button>

    <!-- Calendar Dropdown -->
    <div x-show="showPicker" style="display: none;"
        class="absolute top-full z-50 mt-2 w-full min-w-[300px] bg-white rounded-md border p-3 shadow-md">
        <!-- Header -->
        <div class="flex items-center justify-between space-x-2 pt-1 pb-4">
            <button type="button" @click="prevMonth()"
                class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-gray-200 bg-transparent h-7 w-7 hover:bg-gray-100 opacity-50 hover:opacity-100 transition-opacity">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                </svg>
            </button>
            <div class="text-sm font-medium" x-text="monthName()"></div>
            <button type="button" @click="nextMonth()"
                class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-gray-200 bg-transparent h-7 w-7 hover:bg-gray-100 opacity-50 hover:opacity-100 transition-opacity">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </button>
        </div>

        <!-- Days Header -->
        <div class="flex mb-2">
            <template x-for="day in ['Su','Mo','Tu','We','Th','Fr','Sa']">
                <div class="w-full text-center text-[0.8rem] font-normal text-gray-500" x-text="day"></div>
            </template>
        </div>

        <!-- Days Grid -->
        <div class="grid grid-cols-7 gap-y-2">
            <template x-for="d in days">
                <div class="relative p-0 text-center text-sm focus-within:relative focus-within:z-20">
                    <template x-if="d.day !== ''">
                        <button type="button" @click="selectDate(d.date)"
                            class="inline-flex items-center justify-center rounded-md text-sm font-normal h-8 w-8 transition-colors hover:bg-gray-100 hover:text-gray-900 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                            :class="{ 
                                'bg-black text-white hover:bg-black hover:text-white': d.isSelected,
                                'bg-gray-100': d.isToday && !d.isSelected
                            }">
                            <span x-text="d.day"></span>
                        </button>
                    </template>
                </div>
            </template>
        </div>
    </div>
</div>