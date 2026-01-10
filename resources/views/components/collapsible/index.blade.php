@props(['open' => false])

<div x-data="{ open: {{ $open ? 'true' : 'false' }} }" {{ $attributes->merge(['class' => 'w-full']) }}>
    {{ $slot }}
</div>