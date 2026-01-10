@props(['direction' => 'horizontal'])

<div {{ $attributes->merge(['class' => 'flex h-full w-full ' . ($direction === 'vertical' ? 'flex-col' : 'flex-row')]) }}
    data-panel-group-direction="{{ $direction }}">
    {{ $slot }}
</div>