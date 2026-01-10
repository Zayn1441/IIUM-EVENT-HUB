@props(['orientation' => 'horizontal', 'decorative' => true])

<div
    role="{{ $decorative ? 'none' : 'separator' }}"
    aria-orientation="{{ $decorative ? null : $orientation }}"
    {{ $attributes->merge([
        'class' => 'shrink-0 bg-border ' . ($orientation === 'horizontal' ? 'h-px w-full' : 'h-full w-px')
    ]) }}
></div>
