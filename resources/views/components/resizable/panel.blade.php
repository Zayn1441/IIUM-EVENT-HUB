@props(['defaultSize' => 50, 'minSize' => 0, 'maxSize' => 100])

<div {{ $attributes->merge(['class' => 'relative flex']) }} style="flex-basis: {{ $defaultSize }}%;">
    {{ $slot }}
</div>