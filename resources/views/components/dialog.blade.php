@props(['name', 'title' => '', 'description' => '', 'maxWidth' => 'sm:max-w-lg'])

<div x-data="{ show: false }" x-on:open-dialog.window="if ($event.detail === '{{ $name }}') show = true"
    x-on:close-dialog.window="if ($event.detail === '{{ $name }}') show = false" @keydown.escape.window="show = false"
    style="display: none;" x-show="show" class="fixed inset-0 z-50 flex items-start justify-center sm:items-center">
    <!-- Overlay -->
    <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 transition-opacity" @click="show = false"></div>

    <!-- Content -->
    <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="relative grid w-full {{ $maxWidth }} scale-100 gap-4 bg-white p-6 opacity-100 shadow-lg sm:rounded-lg">
        @if($title)
            <div class="flex flex-col space-y-1.5 text-center sm:text-left">
                <h2 class="text-lg font-semibold leading-none tracking-tight">{{ $title }}</h2>
                @if($description)
                    <p class="text-sm text-gray-500">{{ $description }}</p>
                @endif
            </div>
        @endif

        {{ $slot }}

        <button @click="show = false"
            class="absolute right-4 top-4 rounded-sm opacity-70 ring-offset-white transition-opacity hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-gray-950 focus:ring-offset-2 disabled:pointer-events-none data-[state=open]:bg-gray-100 data-[state=open]:text-gray-500">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
            </svg>
            <span class="sr-only">Close</span>
        </button>
    </div>
</div>