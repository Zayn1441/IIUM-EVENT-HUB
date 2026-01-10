<x-pagination.link {{ $attributes->merge(['class' => 'gap-1 pr-2.5', 'aria-label' => 'Go to next page', 'size' => 'default']) }}>
    <span class="hidden sm:block">Next</span>
    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="m9 18 6-6-6-6" />
    </svg>
</x-pagination.link>