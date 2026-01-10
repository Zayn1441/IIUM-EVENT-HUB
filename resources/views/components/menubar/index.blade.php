<div x-data="{ activeMenu: null }" {{ $attributes->merge(['class' => 'flex h-10 items-center gap-1 rounded-md border bg-background p-1 shadow-sm']) }}>
    {{ $slot }}
</div>