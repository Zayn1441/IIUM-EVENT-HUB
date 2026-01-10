@props([
    'variant' => 'default',
    'size' => 'default',
    'pressed' => false,
])

<button
    type="button"
    x-data="{ pressed: {{ $pressed ? 'true' : 'false' }} }"
    @click="pressed = !pressed"
    :aria-pressed="pressed"
    :data-state="pressed ? 'on' : 'off'"
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium transition-colors hover:bg-muted hover:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 data-[state=on]:bg-accent data-[state=on]:text-accent-foreground [&_svg]:pointer-events-none [&_svg:not([class*=\'size-\'])]:size-4 [&_svg]:shrink-0 ' . 
        match($variant) {
            'default' => 'bg-transparent',
            'outline' => 'border border-input bg-transparent hover:bg-accent hover:text-accent-foreground',
            default => 'bg-transparent',
        } . ' ' .
        match($size) {
            'default' => 'h-9 px-3 min-w-9',
            'sm' => 'h-8 px-2 min-w-8',
            'lg' => 'h-10 px-3 min-w-10',
            default => 'h-9 px-3 min-w-9',
        }
    ]) }}
>
    {{ $slot }}
</button>
