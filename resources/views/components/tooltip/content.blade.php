@props(['side' => 'top', 'sideOffset' => 4])

@php
    $positionClasses = match($side) {
        'top' => 'bottom-full left-1/2 -translate-x-1/2 mb-2 data-[side=top]:slide-in-from-bottom-2',
        'bottom' => 'top-full left-1/2 -translate-x-1/2 mt-2 data-[side=bottom]:slide-in-from-top-2',
        'left' => 'right-full top-1/2 -translate-y-1/2 mr-2 data-[side=left]:slide-in-from-right-2',
        'right' => 'left-full top-1/2 -translate-y-1/2 ml-2 data-[side=right]:slide-in-from-left-2',
        default => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
    };
@endphp

<div
    x-show="open"
    x-transition:enter="animate-in fade-in-0 zoom-in-95 duration-200"
    x-transition:leave="animate-out fade-out-0 zoom-out-95 duration-200"
    {{ $attributes->merge(['class' => 'absolute z-50 overflow-hidden rounded-md bg-primary px-3 py-1.5 text-xs text-primary-foreground shadow-md ' . $positionClasses]) }}
    style="display: none;"
    data-side="{{ $side }}"
>
    {{ $slot }}
</div>
