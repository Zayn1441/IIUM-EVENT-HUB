@props(['isActive' => false, 'icon' => null, 'as' => 'button'])

@php
    $element = $as;
    // If href is present, force it to be an anchor tag unless specified otherwise
    if ($attributes->has('href')) {
        $element = 'a';
    }
@endphp

<{{ $element }} {{ $attributes->merge([
    'class' => 'peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left text-sm outline-none ring-sidebar-ring transition-[width,height,padding] hover:bg-sidebar-accent hover:text-sidebar-accent-foreground focus-visible:ring-2 active:bg-sidebar-accent active:text-sidebar-accent-foreground disabled:pointer-events-none disabled:opacity-50 aria-disabled:pointer-events-none aria-disabled:opacity-50 ' .
        ($isActive ? 'bg-sidebar-accent font-medium text-sidebar-accent-foreground' : '')
]) }}>
    @if($icon)
        {{ $icon }}
    @endif
    {{ $slot }}
</{{ $element }}>