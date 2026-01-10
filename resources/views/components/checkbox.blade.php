@props(['name' => '', 'id' => null, 'value' => '', 'checked' => false, 'disabled' => false])

@php
    $id = $id ?? $name;
@endphp

<div class="flex items-center">
    <input 
        type="checkbox" 
        name="{{ $name }}" 
        id="{{ $id }}" 
        value="{{ $value }}"
        {{ $checked ? 'checked' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'peer h-4 w-4 shrink-0 rounded-sm border border-gray-900 shadow-sm text-black focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 data-[state=checked]:bg-black data-[state=checked]:text-white']) }}
    >
    {{ $slot }}
</div>
