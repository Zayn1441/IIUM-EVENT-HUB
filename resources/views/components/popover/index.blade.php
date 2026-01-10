<div x-data="{ open: false }" {{ $attributes->merge(['class' => 'relative inline-block']) }}>
    {{ $slot }}
</div>