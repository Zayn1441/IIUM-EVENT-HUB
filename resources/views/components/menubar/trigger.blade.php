<button @click="isOpen ? close() : open()" @mouseover="if(activeMenu) open()"
    :class="{ 'bg-accent text-accent-foreground': isOpen }" {{ $attributes->merge(['class' => 'flex cursor-default select-none items-center rounded-sm px-3 py-1.5 text-sm font-medium outline-none focus:bg-accent focus:text-accent-foreground']) }}>
    {{ $slot }}
</button>