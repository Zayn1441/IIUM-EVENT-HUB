@props([
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'defaultValue' => 0,
    'name' => null,
])

<div 
    x-data="{ 
        value: {{ $defaultValue }},
        min: {{ $min }},
        max: {{ $max }},
        get percent() {
            return ((this.value - this.min) / (this.max - this.min)) * 100;
        }
    }"
    {{ $attributes->merge(['class' => 'relative flex w-full touch-none select-none items-center']) }}
>
    <!-- Screen reader / Form input -->
    <input 
        type="range" 
        min="{{ $min }}" 
        max="{{ $max }}" 
        step="{{ $step }}" 
        x-model="value"
        @if($name) name="{{ $name }}" @endif
        class="absolute h-full w-full opacity-0 cursor-pointer z-20"
    >

    <!-- Track -->
    <div class="relative h-2 w-full grow overflow-hidden rounded-full bg-secondary">
        <!-- Range / Fill -->
        <div 
            class="absolute h-full bg-primary"
            :style="`width: ${percent}%`"
        ></div>
    </div>

    <!-- Thumb (Visual only, positioned by percent) -->
    <div 
        class="absolute h-5 w-5 rounded-full border-2 border-primary bg-background ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 z-10 pointer-events-none shadow-sm"
        :style="`left: ${percent}%; transform: translateX(-50%)`"
    ></div>
</div>
