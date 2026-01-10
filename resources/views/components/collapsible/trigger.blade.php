<button @click="open = !open" {{ $attributes->merge(['class' => '']) }}>
    {{ $slot }}
</button>