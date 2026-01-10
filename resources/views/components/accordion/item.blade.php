@props(['value'])

<div class="border-b last:border-b-0">
    <div x-data="{ 
        id: '{{ $value }}', 
        get expanded() { return this.activeItem === this.id }, 
        set expanded(value) { this.activeItem = value ? this.id : null } 
    }">
        {{ $slot }}
    </div>
</div>