@props(['value', 'label' => null])

@php
    $label = $label ?? $slot->toHtml();
@endphp

<div @click="select('{{ $value }}', '{{ $label }}')"
    class="relative flex w-full cursor-default select-none items-center rounded-sm py-1.5 pl-8 pr-2 text-sm outline-none focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 hover:bg-accent hover:text-accent-foreground cursor-pointer"
    :class="{ 'bg-accent text-accent-foreground': value === '{{ $value }}' }">
    <span x-show="value === '{{ $value }}'" class="absolute left-2 flex h-3.5 w-3.5 items-center justify-center">
        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12" />
        </svg>
    </span>
    <span>{{ $slot }}</span>
</div>