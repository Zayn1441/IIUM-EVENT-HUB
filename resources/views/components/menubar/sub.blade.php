<div x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
    {{ $slot }}
</div>