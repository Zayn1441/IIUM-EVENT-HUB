@props(['type' => 'line', 'data' => [], 'options' => []])

<div x-data="{
    chart: null,
    init() {
        this.chart = new Chart(this.$refs.canvas, {
            type: '{{ $type }}',
            data: {{ json_encode($data) }},
            options: {{ json_encode($options) }}
        });
    }
}" {{ $attributes->merge(['class' => 'w-full h-full']) }}>
    <canvas x-ref="canvas"></canvas>
</div>