@props(['href' => '#', 'active' => false])

@if($active)
    <span role="link" aria-disabled="true" aria-current="page" {{ $attributes->merge(['class' => 'font-normal text-gray-900']) }}>
        {{ $slot }}
    </span>
@else
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'transition-colors hover:text-gray-900']) }}>
        {{ $slot }}
    </a>
@endif