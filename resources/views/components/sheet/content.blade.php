@props(['side' => 'right'])

@php
    $sideClasses = match ($side) {
        'left' => 'inset-y-0 left-0 h-full w-3/4 border-r sm:max-w-sm data-[state=closed]:slide-out-to-left data-[state=open]:slide-in-from-left',
        'right' => 'inset-y-0 right-0 h-full w-3/4 border-l sm:max-w-sm data-[state=closed]:slide-out-to-right data-[state=open]:slide-in-from-right',
        'top' => 'inset-x-0 top-0 h-auto border-b data-[state=closed]:slide-out-to-top data-[state=open]:slide-in-from-top',
        'bottom' => 'inset-x-0 bottom-0 h-auto border-t data-[state=closed]:slide-out-to-bottom data-[state=open]:slide-in-from-bottom',
        default => 'inset-y-0 right-0 h-full w-3/4 border-l sm:max-w-sm data-[state=closed]:slide-out-to-right data-[state=open]:slide-in-from-right',
    };
@endphp

<div x-show="open" class="fixed inset-0 z-50 flex" style="display: none;" role="dialog" aria-modal="true">
    <!-- Overlay -->
    <div x-show="open" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 transition-opacity" @click="open = false"></div>

    <!-- Content -->
    <div x-show="open" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
        x-transition:enter-start="{{ $side === 'right' ? 'translate-x-full' : ($side === 'left' ? '-translate-x-full' : ($side === 'top' ? '-translate-y-full' : 'translate-y-full')) }}"
        x-transition:enter-end="translate-x-0 translate-y-0"
        x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
        x-transition:leave-start="translate-x-0 translate-y-0"
        x-transition:leave-end="{{ $side === 'right' ? 'translate-x-full' : ($side === 'left' ? '-translate-x-full' : ($side === 'top' ? '-translate-y-full' : 'translate-y-full')) }}"
        {{ $attributes->merge(['class' => 'fixed z-50 gap-4 bg-background p-6 shadow-lg transition ease-in-out data-[state=open]:animate-in data-[state=closed]:animate-out ' . $sideClasses]) }}>
        <button type="button"
            class="absolute right-4 top-4 rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none data-[state=open]:bg-secondary"
            @click="open = false">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18" />
                <path d="m6 6 18 18" />
            </svg>
            <span class="sr-only">Close</span>
        </button>
        {{ $slot }}
    </div>
</div>