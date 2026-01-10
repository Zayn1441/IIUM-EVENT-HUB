<div x-data="{ 
        activeItem: null,
        setActiveItem(item) { this.activeItem = item },
        clearActiveItem() { this.activeItem = null },
        itemIsActive(item) { return this.activeItem === item }
    }" class="relative z-10 flex max-w-max flex-1 items-center justify-center p-1" @mouseleave="clearActiveItem()">
    {{ $slot }}
</div>