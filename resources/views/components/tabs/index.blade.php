@props(['defaultValue' => ''])

<div x-data="{ value: '{{ $defaultValue }}' }" {{ $attributes->merge(['class' => 'flex flex-col gap-2']) }}>
    {{ $slot }}
</div>