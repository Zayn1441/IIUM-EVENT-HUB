@props(['variant' => 'default'])

@php
    $baseClass = "inline-flex items-center justify-center rounded-md border px-2 py-0.5 text-xs font-medium w-fit whitespace-nowrap shrink-0 transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2";
    $variants = [
        'default' => "border-transparent bg-primary text-primary-foreground hover:bg-primary/80 bg-black text-white",
        'secondary' => "border-transparent bg-gray-100 text-gray-900 hover:bg-gray-200",
        'destructive' => "border-transparent bg-red-600 text-white hover:bg-red-700",
        'outline' => "text-gray-900 border-gray-200 hover:bg-gray-100",
    ];
    $classes = $baseClass . ' ' . ($variants[$variant] ?? $variants['default']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>