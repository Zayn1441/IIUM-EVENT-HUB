@props(['checked' => false])

<div {{ $attributes->merge(['class' => 'relative flex cursor-default select-none items-center rounded-sm py-1.5 pr-2 pl-8 text-sm outline-none focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 hover:bg-accent hover:text-accent-foreground cursor-pointer']) }}>
    <span class="absolute left-2 flex h-3.5 w-3.5 items-center justify-center">
        @if($checked)
            <svg class="h-2 w-2 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
            </svg>
        @endif
    </span>
    {{ $slot }}
</div>