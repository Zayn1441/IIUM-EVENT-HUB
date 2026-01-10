<div x-data="{ 
        toasts: [],
        add(event) {
            const id = Date.now();
            const toast = {
                id,
                title: event.detail.title || '',
                description: event.detail.description || '',
                type: event.detail.type || 'default', // default, success, error, warning, info
                duration: event.detail.duration || 3000
            };
            this.toasts.push(toast);
            setTimeout(() => this.remove(id), toast.duration);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }" @notify.window="add($event)"
    class="fixed top-0 right-0 z-[100] flex max-h-screen w-full flex-col p-4 sm:top-0 sm:right-0 sm:bottom-auto md:max-w-[420px]"
    style="pointer-events: none;">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto relative flex w-full items-center justify-between space-x-4 overflow-hidden rounded-md border p-6 pr-8 shadow-lg transition-all"
            :class="{
                'bg-background text-foreground border-border': toast.type === 'default',
                'destructive group border-destructive bg-destructive text-destructive-foreground': toast.type === 'error' || toast.type === 'destructive',
                'bg-green-100 border-green-200 text-green-900': toast.type === 'success',
                'bg-yellow-100 border-yellow-200 text-yellow-900': toast.type === 'warning',
                'bg-blue-100 border-blue-200 text-blue-900': toast.type === 'info'
            }">
            <div class="grid gap-1">
                <template x-if="toast.title">
                    <div x-text="toast.title" class="text-sm font-semibold"></div>
                </template>
                <template x-if="toast.description">
                    <div x-text="toast.description" class="text-sm opacity-90"></div>
                </template>
            </div>
            <button @click="remove(toast.id)"
                class="absolute right-2 top-2 rounded-md p-1 opacity-0 transition-opacity hover:text-foreground focus:opacity-100 focus:outline-none focus:ring-2 group-hover:opacity-100"
                :class="{
                    'text-foreground/50 hover:text-foreground': toast.type === 'default',
                    'text-red-300 hover:text-red-50': toast.type === 'error' || toast.type === 'destructive',
                    'text-green-700 hover:text-green-900': toast.type === 'success'
                }">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" x2="6" y1="6" y2="18" />
                    <line x1="6" x2="18" y1="6" y2="18" />
                </svg>
            </button>
        </div>
    </template>
</div>