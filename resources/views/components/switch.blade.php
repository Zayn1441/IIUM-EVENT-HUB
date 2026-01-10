@props(['name' => null, 'value' => '1', 'checked' => false])

<div 
    x-data="{ 
        checked: {{ $checked ? 'true' : 'false' }},
        toggle() { this.checked = !this.checked }
    }"
    class="flex items-center"
>
    <!-- Hidden input for form submission -->
    @if($name)
        <input type="hidden" name="{{ $name }}" :value="checked ? '{{ $value }}' : '0'">
        <input 
            type="checkbox" 
            name="{{ $name }}" 
            value="{{ $value }}" 
            x-model="checked" 
            class="hidden"
        >
    @endif

    <button 
        type="button" 
        role="switch" 
        :aria-checked="checked" 
        @click="toggle"
        {{ $attributes->merge(['class' => 'peer inline-flex h-[1.15rem] w-8 shrink-0 cursor-pointer items-center rounded-full border border-transparent transition-all outline-none focus-visible:ring-[3px] focus-visible:border-ring focus-visible:ring-ring/50 disabled:cursor-not-allowed disabled:opacity-50']) }}
        :class="{ 
            'bg-primary': checked, 
            'bg-input': !checked 
        }"
    >
        <span 
            class="pointer-events-none block size-4 rounded-full bg-background shadow-lg ring-0 transition-transform"
            :class="{ 
                'translate-x-[calc(100%-2px)]': checked, 
                'translate-x-0': !checked 
            }"
        ></span>
    </button>
</div>