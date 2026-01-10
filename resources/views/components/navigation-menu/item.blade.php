@props(['value'])

<li {{ $attributes->merge(['class' => 'relative']) }} @mouseenter="setActiveItem('{{ $value }}')">
    {{ $slot }}
</li>