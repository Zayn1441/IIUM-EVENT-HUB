<div x-show="open" x-transition:enter="transition ease-out duration-100"
    x-transition:enter-start="opacity-0 scale-95 translate-x-1"
    x-transition:enter-end="opacity-100 scale-100 translate-x-0" x-transition:leave="transition ease-in duration-75"
    x-transition:leave-start="opacity-100 scale-100 translate-x-0"
    x-transition:leave-end="opacity-0 scale-95 translate-x-1" {{ $attributes->merge(['class' => 'absolute top-0 left-full z-50 min-w-[8rem] ml-1 overflow-hidden rounded-md border bg-popover p-1 text-popover-foreground shadow-md']) }}
    style="display: none;">
    {{ $slot }}
</div>