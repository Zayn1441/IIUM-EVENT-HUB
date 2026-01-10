@props(['value'])

<div x-data="{ 
        get isOpen() { return activeMenu === '{{ $value }}' },
        open() { activeMenu = '{{ $value }}' },
        close() { if(this.isOpen) activeMenu = null }
    }" class="relative" @click.outside="close">
    {{ $slot }}
</div>