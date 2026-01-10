@props(['value' => 0])

<div {{ $attributes->merge(['class' => 'relative h-2 w-full overflow-hidden rounded-full bg-primary/20']) }}>
    <div class="h-full w-full flex-1 bg-primary transition-all" style="transform: translateX(-{{ 100 - $value }}%)">
    </div>
</div>