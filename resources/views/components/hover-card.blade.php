<div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative inline-block">
    <div @mouseenter="open = true" @mouseleave="open = false">
        {{ $trigger }}
    </div>

    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 w-64 rounded-md border bg-white p-4 shadow-md outline-none mt-2" style="display: none;">
        {{ $content }}
    </div>
</div>