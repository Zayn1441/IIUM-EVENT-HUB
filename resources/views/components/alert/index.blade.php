@props(['variant' => 'default', 'title' => null])

@php
    $baseClasses = "relative w-full rounded-lg border px-4 py-3 text-sm flex gap-x-3 items-start [&>svg]:mt-0.5 [&>svg]:w-4 [&>svg]:h-4";

    $variants = [
        'default' => "bg-white text-gray-900 border-gray-200 [&>svg]:text-gray-900",
        'destructive' => "border-red-500/50 text-red-700 bg-red-50 [&>svg]:text-red-600",
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['default']);
@endphp

<div {{ $attributes->merge(['class' => $classes, 'role' => 'alert']) }}>
    {{ $slot }}
</div>