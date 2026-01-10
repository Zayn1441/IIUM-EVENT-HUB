<button @click="expanded = !expanded" type="button"
    class="flex flex-1 items-center justify-between w-full py-4 text-sm font-medium transition-all hover:underline text-left [&[aria-expanded=true]>svg]:rotate-180"
    :aria-expanded="expanded">
    {{ $slot }}
    <svg class="w-4 h-4 shrink-0 transition-transform duration-200 text-gray-500" fill="none" stroke="currentColor"
        viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
    </svg>
</button>