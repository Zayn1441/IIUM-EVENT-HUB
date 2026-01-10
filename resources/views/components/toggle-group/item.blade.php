@props([
    'value',
    'variant' => null,
    'size' => null,
])

<button
    type="button"
    @click="toggle('{{ $value }}')"
    :data-state="(type === 'single' ? value === '{{ $value }}' : value.includes('{{ $value }}')) ? 'on' : 'off'"
    :class="{
        'bg-background shadow-sm hover:bg-muted hover:text-muted-foreground': (variant || $data.variant) === 'outline',
        'bg-transparent hover:bg-muted hover:text-muted-foreground': (variant || $data.variant) === 'default',
        'h-9 px-3': (size || $data.size) === 'default',
        'h-8 px-2': (size || $data.size) === 'sm',
        'h-10 px-3': (size || $data.size) === 'lg',
        'bg-accent text-accent-foreground': (type === 'single' ? value === '{{ $value }}' : value.includes('{{ $value }}'))
    }"
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 ring-offset-background']) }}
>
    {{ $slot }}
</button>
