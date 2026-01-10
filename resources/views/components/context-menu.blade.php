@props(['items' => []])

<div x-data="{
        open: false,
        top: 0,
        left: 0,
        handleContextMenu(e) {
            e.preventDefault();
            this.open = true;
            this.left = e.clientX;
            this.top = e.clientY;
        }
    }" @contextmenu="handleContextMenu($event)" @click.away="open = false" class="relative inline-block">
    {{ $slot }}

    <div x-show="open" :style="`top: ${top}px; left: ${left}px; position: fixed;`"
        class="z-50 min-w-[8rem] overflow-hidden rounded-md border bg-white p-1 shadow-md text-gray-900 animate-in fade-in-80 zoom-in-95"
        style="display: none;">
        @foreach($items as $item)
            <div
                class="relative flex cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none hover:bg-gray-100 hover:text-gray-900 cursor-pointer">
                {{ $item }}
            </div>
        @endforeach
    </div>
</div>