@props(['value' => ''])

<div data-value="{{ $value }}"
    class="relative flex cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none aria-selected:bg-gray-100 aria-selected:text-gray-900 data-[disabled]:pointer-events-none data-[disabled]:opacity-50 hover:bg-gray-100"
    role="option">
    {{ $slot }}
</div>