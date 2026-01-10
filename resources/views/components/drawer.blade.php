@props(['name', 'direction' => 'right'])

@php
    $directions = [
        'right' => 'inset-y-0 right-0 h-full w-3/4 border-l sm:max-w-sm',
        'left' => 'inset-y-0 left-0 h-full w-3/4 border-r sm:max-w-sm',
        'bottom' => 'inset-x-0 bottom-0 mt-24 h-[96%] rounded-t-[10px] border-t',
        'top' => 'inset-x-0 top-0 mb-24 h-[96%] rounded-b-[10px] border-b',
    ];
    $positionClass = $directions[$direction] ?? $directions['right'];
@endphp

<div x-data="{ open: false }" x-on:open-drawer.window="if ($event.detail === '{{ $name }}') open = true"
    x-on:close-drawer.window="if ($event.detail === '{{ $name }}') open = false" @keydown.escape.window="open = false"
    class="fixed inset-0 z-50 flex bg-black/50" style="display: none;" x-show="open"
    x-transition:enter="fade-in-0 animate-in" x-transition:leave="fade-out-0 animate-out">
    <!-- Overlay click to close -->
    <div class="fixed inset-0 z-50" @click="open = false"></div>

    <div class="fixed z-50 bg-white p-6 shadow-lg transition ease-in-out duration-300 {{ $positionClass }}"
        x-show="open" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
        x-transition:enter-start="{{ $direction === 'right' ? 'translate-x-full' : ($direction === 'left' ? '-translate-x-full' : ($direction === 'bottom' ? 'translate-y-full' : '-translate-y-full')) }}"
        x-transition:enter-end="translate-x-0 translate-y-0"
        x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
        x-transition:leave-start="translate-x-0 translate-y-0"
        x-transition:leave-end="{{ $direction === 'right' ? 'translate-x-full' : ($direction === 'left' ? '-translate-x-full' : ($direction === 'bottom' ? 'translate-y-full' : '-translate-y-full')) }}">
        {{ $slot }}
    </div>
</div>