@props(['name', 'title' => null, 'description' => null, 'confirmText' => 'Confirm', 'cancelText' => null, 'confirmStyle' => 'danger'])

<div x-data="{ open: false }" x-on:open-modal.window="if ($event.detail === '{{ $name }}') open = true"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}') open = false" @keydown.escape.window="open = false"
    class="relative z-50 w-auto h-auto">
    <!-- Overlay -->
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50"
        style="display: none;"></div>

    <!-- Content -->
    <div x-show="open" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 zoom-out-95"
        x-transition:enter-end="opacity-100 scale-100 zoom-in-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 zoom-in-100"
        x-transition:leave-end="opacity-0 scale-95 zoom-out-95"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" style="display: none;">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl sm:max-w-lg w-full p-6 border border-gray-200 dark:border-gray-700"
            @click.away="open = false">
            @if($title)
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h3>
                @if($description)
                    <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">{{ $description }}</p>
                @endif

                <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    @if($cancelText)
                        <button @click="open = false"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background border border-input hover:bg-accent hover:text-accent-foreground h-10 py-2 px-4">
                            {{ $cancelText }}
                        </button>
                    @endif

                    {{ $slot }}
                </div>
            @else
                {{ $slot }}
            @endif
        </div>
    </div>
</div>