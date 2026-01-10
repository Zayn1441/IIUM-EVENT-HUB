@props(['defaultOpen' => true])

<div x-data="{ 
        open: {{ $defaultOpen ? 'true' : 'false' }},
        openMobile: false,
        isMobile: window.innerWidth < 768,
        toggle() { this.open = !this.open; this.setCookie() },
        toggleMobile() { this.openMobile = !this.openMobile },
        setCookie() { document.cookie = `sidebar_state=${this.open}; path=/; max-age=604800` },
        init() {
            this.isMobile = window.innerWidth < 768;
            window.addEventListener('resize', () => {
                this.isMobile = window.innerWidth < 768;
            });
            // Try to read cookie
            const match = document.cookie.match(/(^| )sidebar_state=([^;]+)/);
            if (match) this.open = match[2] === 'true';
        }
    }" class="group/sidebar-wrapper flex min-h-screen w-full"
    style="--sidebar-width: 16rem; --sidebar-width-icon: 3rem;" {{ $attributes }}>
    {{ $slot }}
</div>