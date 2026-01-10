<nav {{ $attributes->merge(['class' => 'mx-auto flex w-full justify-center', 'role' => 'navigation', 'aria-label' => 'pagination']) }}>
    {{ $slot }}
</nav>