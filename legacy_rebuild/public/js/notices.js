const Notices = {
    async init() {
        this.loadNotices();
    },

    async loadNotices() {
        const container = document.getElementById('notices-container');
        if (!container) return;

        try {
            const response = await App.request('notices/index.php');
            const notices = response.data;

            if (notices.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-500 py-8">No notices found.</div>';
                return;
            }

            container.innerHTML = notices.map(notice => `
                <div class="bg-gray-50 p-4 rounded-lg flex items-start space-x-4 border-l-4 ${this.getBorderColor(notice.type)}">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">${notice.title}</h4>
                        <p class="text-gray-600 mt-1">${notice.message}</p>
                        ${notice.action_url ? `<a href="#${notice.action_url.replace(/.*#/, '')}" class="text-primary-600 text-sm mt-2 inline-block hover:underline">View Details</a>` : ''}
                    </div>
                    <span class="text-xs text-gray-400">${new Date(notice.created_at).toLocaleDateString()}</span>
                </div>
            `).join('');

        } catch (error) {
            container.innerHTML = '<div class="text-center text-red-500 py-8">Failed to load notices.</div>';
        }
    },

    getBorderColor(type) {
        switch (type) {
            case 'system': return 'border-blue-500';
            case 'admin_message': return 'border-red-500';
            case 'event_update': return 'border-yellow-500';
            default: return 'border-gray-500';
        }
    },

    async clearAll() {
        if (!confirm('Clear all notices?')) return;
        try {
            await App.request('notices/clear.php', 'DELETE');
            this.loadNotices(); // Reload (empty)
            App.showToast('Notices cleared');
        } catch (error) {
            App.showToast('Failed to clear notices', 'error');
        }
    }
};
