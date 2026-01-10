<div x-data="{ 
        open: false, 
        timer: null,
        show() {
            if (this.timer) clearTimeout(this.timer);
            this.timer = setTimeout(() => { this.open = true }, this.delay || 0);
        },
        hide() {
            if (this.timer) clearTimeout(this.timer);
            this.open = false;
        }
    }" {{ $attributes->merge(['class' => 'relative inline-block']) }} @mouseleave="hide()">
    {{ $slot }}
</div>