@props(['isActive' => false, 'size' => 'icon'])

@php
    $baseClasses = 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50';
    $variantClasses = $isActive ? 'border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground' : 'hover:bg-accent hover:text-accent-foreground';
    $sizeClasses = $size === 'icon' ? 'h-9 w-9' : 'h-9 px-4 py-2';
@endphp

<a {{ $attributes->merge(['class' => "$baseClasses $variantClasses $sizeClasses", 'aria-current' => $isActive ? 'page' : null]) }}>
    {{ $slot }}
</a>