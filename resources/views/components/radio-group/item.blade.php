@props(['id', 'name', 'value'])

<div class="flex items-center space-x-2">
    <input type="radio" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" {{ $attributes->merge(['class' => 'peer sr-only']) }}>
    <label for="{{ $id }}"
        class="aspect-square h-4 w-4 rounded-full border border-primary text-primary shadow focus:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 peer-checked:bg-white flex items-center justify-center cursor-pointer">
        <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"
            class="h-2.5 w-2.5 fill-current text-primary opacity-0 peer-checked:opacity-100 transition-opacity"
            xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="12" />
        </svg>
    </label>
    {{ $slot }}
</div>