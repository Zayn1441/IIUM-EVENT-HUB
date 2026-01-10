@props(['heading'])

<div class="overflow-hidden p-1 text-gray-900">
    <div class="px-2 py-1.5 text-xs font-medium text-gray-500">{{ $heading }}</div>
    {{ $slot }}
</div>