<div @click="open = !open" {{ $attributes->merge(['class' => 'inline-flex cursor-pointer']) }}>
    {{ $slot }}
</div>