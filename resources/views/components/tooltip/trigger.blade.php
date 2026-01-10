<div @mouseenter="show()" @focus="show()" @blur="hide()" {{ $attributes->merge(['class' => 'inline-block']) }}>
    {{ $slot }}
</div>