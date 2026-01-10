<div x-show="open" x-collapse {{ $attributes->merge(['class' => 'overflow-hidden']) }} style="display: none;">
    {{ $slot }}
</div>