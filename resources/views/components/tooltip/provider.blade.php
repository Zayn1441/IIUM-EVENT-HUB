@props(['delayDuration' => 0])

<div x-data="{ delay: {{ $delayDuration }} }" {{ $attributes }}>
    {{ $slot }}
</div>