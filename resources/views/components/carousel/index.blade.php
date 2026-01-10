@props(['options' => [], 'orientation' => 'horizontal'])

<div x-data="{
        carousel: null,
        init() {
            this.carousel = EmblaCarousel(this.$refs.viewport, {
                ...{{ json_encode($options) }},
                axis: '{{ $orientation === 'horizontal' ? 'x' : 'y' }}'
            });
        },
        scrollPrev() { this.carousel.scrollPrev() },
        scrollNext() { this.carousel.scrollNext() }
    }" class="relative" {{ $attributes }}>
    {{ $slot }}
</div>