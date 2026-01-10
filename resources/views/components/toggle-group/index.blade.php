@props([
    'type' => 'single', // 'single' or 'multiple'
    'defaultValue' => null,
    'variant' => 'default',
    'size' => 'default',
])

<div
    x-data="{
        type: '{{ $type }}',
        value: {{ $type === 'multiple' ? '[]' : ($defaultValue ? "'$defaultValue'" : 'null') }},
        variant: '{{ $variant }}',
        size: '{{ $size }}',
        toggle(itemValue) {
            if (this.type === 'multiple') {
                if (this.value.includes(itemValue)) {
                    this.value = this.value.filter(v => v !== itemValue);
                } else {
                    this.value.push(itemValue);
                }
            } else {
                this.value = this.value === itemValue ? null : itemValue;
            }
        }
    }"
    {{ $attributes->merge(['class' => 'flex items-center justify-center gap-1']) }}
    role="group"
>
    {{ $slot }}
</div>
