@props(['value'])

<div x-show="itemIsActive('{{ $value }}')" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    class="absolute left-0 top-full w-full sm:w-auto mt-2 overflow-hidden rounded-md border bg-popover text-popover-foreground shadow-lg focus:outline-none md:w-[500px] lg:w-[600px]"
    style="display: none;">
    {{ $slot }}
</div>