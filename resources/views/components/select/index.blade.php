@props(['name', 'placeholder' => 'Select an option', 'defaultValue' => ''])

<div x-data="{ 
        open: false,
        value: '{{ $defaultValue }}',
        label: '{{ $placeholder }}',
        options: {},
        init() {
            // If default value is provided, try to find label after items register
            if (this.value) {
                // This is a bit tricky in Alpine/Blade without centralized store. 
                // We'll trust the child items to register themselves or check on render.
            }
        },
        select(val, lbl) {
            this.value = val;
            this.label = lbl;
            this.open = false;
        }
    }" class="relative w-full" @click.away="open = false">
    <input type="hidden" name="{{ $name }}" :value="value">
    {{ $slot }}
</div>