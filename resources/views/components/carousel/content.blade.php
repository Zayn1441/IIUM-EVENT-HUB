@props(['orientation' => 'horizontal'])

<div x-ref="viewport" class="overflow-hidden">
    <div class="flex {{ $orientation === 'horizontal' ? '-ml-4' : '-mt-4 flex-col' }}">
        {{ $slot }}
    </div>
</div>