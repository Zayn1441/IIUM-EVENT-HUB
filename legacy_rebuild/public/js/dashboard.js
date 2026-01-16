const Dashboard = {
    async init() {
        this.loadStats();
        this.loadEvents();
    },

    async loadStats() {
        // Mock stats for now or fetch from specific endpoint if available
        // document.getElementById('total-events').innerText = '...';
    },

    async loadEvents() {
        const container = document.getElementById('events-container');
        if (!container) return;

        container.innerHTML = '<div class="col-span-3 text-center py-10"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600 inline-block"></div></div>';

        try {
            const response = await App.request('events/index.php');
            const events = response.data;

            if (events.length === 0) {
                container.innerHTML = '<div class="col-span-3 text-center py-10 text-gray-500">No upcoming events found.</div>';
                return;
            }

            container.innerHTML = events.map(event => this.renderEventCard(event)).join('');
        } catch (error) {
            console.error(error);
            container.innerHTML = '<div class="col-span-3 text-center py-10 text-red-500">Failed to load events.</div>';
        }
    },

    renderEventCard(event) {
        // Formatting date/time
        const date = new Date(`${event.date}T${event.time}`);
        const dateStr = date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
        const timeStr = date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });

        return `
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="h-48 bg-gray-200 relative overflow-hidden">
                    ${event.image_path ? `<img src="${event.image_path}" alt="${event.title}" class="w-full h-full object-cover">` : `<div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>`}
                    <div class="absolute top-2 right-2 bg-white px-2 py-1 rounded-md text-xs font-bold shadow text-primary-600 uppercase">
                        ${event.category}
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        ${dateStr} • ${timeStr}
                    </div>
                    <a href="#/events/${event.id}" class="block mt-1 text-lg leading-tight font-semibold text-gray-900 hover:underline truncate">${event.title}</a>
                    <p class="mt-2 text-gray-500 text-sm line-clamp-2">${event.description}</p>
                    
                    <div class="mt-4 flex items-center justify-between">
                         <div class="flex items-center text-xs text-gray-500">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            ${event.location}
                        </div>
                        ${event.is_starpoints ? '<span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded-full font-medium">Starpoints</span>' : ''}
                    </div>
                </div>
            </div>
        `;
    }
};
