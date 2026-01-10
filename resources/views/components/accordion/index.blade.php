@props(['active' => null])

<div x-data="{ activeItem: '{{ $active }}' }" {{ $attributes->merge(['class' => 'space-y-1']) }}>
    {{ $slot }}
</div>