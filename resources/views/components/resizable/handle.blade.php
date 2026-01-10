@props(['withHandle' => false])

<div
    x-data="{
        dragging: false,
        startX: 0,
        startY: 0,
        startPrevSize: 0,
        startNextSize: 0,
        direction: 'horizontal',
        init() {
            this.direction = this.$el.closest('[data-panel-group-direction]').dataset.panelGroupDirection;
        },
        startDrag(e) {
            e.preventDefault();
            this.dragging = true;
            this.startX = e.clientX;
            this.startY = e.clientY;
            
            const prevPanel = this.$el.previousElementSibling;
            const nextPanel = this.$el.nextElementSibling;
            
            if (!prevPanel || !nextPanel) return;
            
            this.startPrevSize = prevPanel.getBoundingClientRect()[this.direction === 'horizontal' ? 'width' : 'height'];
            this.startNextSize = nextPanel.getBoundingClientRect()[this.direction === 'horizontal' ? 'width' : 'height'];
            
            window.addEventListener('mousemove', this.handleDrag);
            window.addEventListener('mouseup', this.stopDrag);
        },
        handleDrag(e) {
            if (!this.dragging) return;
            
            const delta = this.direction === 'horizontal' ? e.clientX - this.startX : e.clientY - this.startY;
            const prevPanel = this.$el.previousElementSibling;
            const containerSize = this.$el.parentElement.getBoundingClientRect()[this.direction === 'horizontal' ? 'width' : 'height'];
            
            // Calculate new percentage
            // This is a naive implementation; real resizing needs robust percentage logic
            // For now, we adjust flex-basis based on pixel delta converted to %
            
            // It's cleaner to skip advanced logic here and just use standard resizer behavior or library if needed.
            // But let's try a simple pixel adjustment on the 'style' attribute.
            
            const newPrevSize = this.startPrevSize + delta;
            
            // Converting to percentage for flex-basis to remain responsive is ideal
             const newPrevPercent = (newPrevSize / containerSize) * 100;
             
             if(prevPanel) prevPanel.style.flexBasis = `${newPrevPercent}%`;
             // Note: Next panel usually flexes automatically if it's flex-1, or we adjust both. 
             // If both are fixed %, we need to adjust next panel too.
             // Assuming basic flex behavior for now.
        },
        stopDrag() {
            this.dragging = false;
            window.removeEventListener('mousemove', this.handleDrag);
            window.removeEventListener('mouseup', this.stopDrag);
        }
    }"
    @mousedown="startDrag"
    class="relative flex w-px items-center justify-center bg-border after:absolute after:inset-y-0 after:left-1/2 after:w-1 after:-translate-x-1/2 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring focus-visible:ring-offset-1 data-[panel-group-direction=vertical]:h-px data-[panel-group-direction=vertical]:w-full data-[panel-group-direction=vertical]:after:left-0 data-[panel-group-direction=vertical]:after:h-1 data-[panel-group-direction=vertical]:after:w-full data-[panel-group-direction=vertical]:after:-translate-y-1/2 data-[panel-group-direction=vertical]:after:translate-x-0 [&[data-panel-group-direction=vertical]>div]:rotate-90 hover:bg-primary/50 cursor-col-resize cursor-row-resize"
    :class="{ 'cursor-col-resize': direction === 'horizontal', 'cursor-row-resize': direction === 'vertical' }"
    {{ $attributes }}
>
    @if($withHandle)
        <div class="z-10 flex h-4 w-3 items-center justify-center rounded-sm border bg-border">
            <svg class="h-2.5 w-2.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="12" r="1"/><circle cx="9" cy="5" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="15" cy="19" r="1"/></svg>
        </div>
    @endif
</div>
