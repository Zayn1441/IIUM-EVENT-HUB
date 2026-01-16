const Router = {
    routes: {},

    init() {
        window.addEventListener('hashchange', () => this.handleRoute());
        this.handleRoute(); // Handle initial load
    },

    add(path, handler) {
        this.routes[path] = handler;
    },

    async handleRoute() {
        let hash = window.location.hash || '#/';

        // Find matching route
        let handler = this.routes[hash];
        let params = [];

        if (!handler) {
            // Try regex matching
            for (const path in this.routes) {
                if (path.includes(':')) {
                    const pattern = new RegExp('^' + path.replace(/:[^\s/]+/g, '([^/]+)') + '$');
                    const match = hash.match(pattern);
                    if (match) {
                        handler = this.routes[path];
                        params = match.slice(1);
                        break;
                    }
                }
            }
        }

        handler = handler || this.routes['404'] || this.routes['#/'];

        if (handler) {
            await handler(...params);
        }
    },

    navigate(path) {
        window.location.hash = path;
    },

    async loadView(viewName) {
        const app = document.getElementById('app');
        app.innerHTML = '<div class="flex justify-center items-center h-screen"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div></div>';

        try {
            const response = await fetch(`views/${viewName}.html`);
            const html = await response.text();
            app.innerHTML = html;
        } catch (error) {
            console.error('Failed to load view:', error);
            app.innerHTML = '<h1 class="text-red-500">Error loading page</h1>';
        }
    }
};
