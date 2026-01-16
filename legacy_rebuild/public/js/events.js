const Events = {
    initCreate() {
        const form = document.getElementById('create-event-form');
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerText = 'Creating...';

            const formData = new FormData(form);
            // Handle checkboxes
            if (!formData.has('is_starpoints')) {
                formData.append('is_starpoints', 0);
            } else {
                formData.set('is_starpoints', 1);
            }

            const data = Object.fromEntries(formData.entries());

            try {
                await App.request('events/store.php', 'POST', data);
                App.showToast('Event created successfully!');
                Router.navigate('#/dashboard');
            } catch (error) {
                const msg = error.errors ? Object.values(error.errors)[0] : (error.message || 'Failed to create event');
                App.showToast(msg, 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Create Event';
            }
        });
    },

    async initShow(id) {
        const container = document.getElementById('event-details-container');
        try {
            const response = await App.request(`events/show.php?id=${id}`);
            const event = response.data;

            // Format Data
            const date = new Date(`${event.date}T${event.time}`);
            const fullDate = date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            const time = date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });

            const isOwner = Auth.user && (Auth.user.id == event.user_id || Auth.user.is_admin);

            container.innerHTML = `
                <div class="relative h-64 sm:h-80 md:h-96 w-full bg-gray-200">
                    ${event.image_path ? `<img src="${event.image_path}" class="w-full h-full object-cover">` : ''}
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-6 text-white">
                        <div class="max-w-7xl mx-auto">
                            <span class="bg-primary-600 text-xs font-bold px-2 py-1 rounded uppercase tracking-wide">${event.category}</span>
                            <h1 class="text-3xl md:text-4xl font-bold mt-2">${event.title}</h1>
                            <p class="mt-2 text-lg opacity-90">Organized by ${event.organizer_name || event.organizer}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="md:col-span-2 space-y-6">
                        <div class="prose max-w-none text-gray-700">
                            <h3 class="text-xl font-semibold mb-2">About this event</h3>
                            <p>${event.description.replace(/\n/g, '<br>')}</p>
                        </div>

                         ${event.participation_link ? `
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                            <h4 class="font-semibold text-blue-800">Participation Link</h4>
                            <a href="${event.participation_link}" target="_blank" class="text-blue-600 hover:underline break-all">${event.participation_link}</a>
                        </div>` : ''}
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                            <h3 class="font-semibold text-lg mb-4">Event Details</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <div>
                                        <p class="font-medium text-gray-900">Date</p>
                                        <p class="text-gray-600">${fullDate}</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <div>
                                        <p class="font-medium text-gray-900">Time</p>
                                        <p class="text-gray-600">${time}</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <div>
                                        <p class="font-medium text-gray-900">Location</p>
                                        <p class="text-gray-600">${event.location}</p>
                                    </div>
                                </div>
                                ${event.capacity ? `
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <div>
                                        <p class="font-medium text-gray-900">Capacity</p>
                                        <p class="text-gray-600">${event.capacity}</p>
                                    </div>
                                </div>` : ''}
                                ${event.is_starpoints ? `
                                <div class="flex items-center text-yellow-700 bg-yellow-50 p-2 rounded">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <span class="font-bold text-xs uppercase">Starpoints Available</span>
                                </div>` : ''}
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="mt-6 space-y-3">
                                <button id="register-btn" onclick="Events.registerEvent(${event.id})" class="w-full bg-primary-600 text-white rounded-md py-2 font-semibold hover:bg-primary-700 transition">Register Now</button>
                                
                                ${isOwner ? `
                                <div class="border-t border-gray-100 pt-4 mt-4 grid grid-cols-2 gap-2">
                                    <button onclick="Events.deleteEvent(${event.id})" class="text-red-600 border border-red-200 rounded py-1 text-sm hover:bg-red-50">Delete Event</button>
                                    <!-- Edit would link to another route -->
                                     <button onclick="Router.navigate('#/events/${event.id}/edit')" class="text-gray-600 border border-gray-200 rounded py-1 text-sm hover:bg-gray-50">Edit Event</button>
                                </div>
                                ` : `
                                <div class="mt-4 text-center">
                                     <button onclick="Events.reportEvent(${event.id})" class="text-gray-400 hover:text-gray-600 text-sm underline">Report this event</button>
                                </div>
                                `}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } catch (error) {
            container.innerHTML = `<div class="p-8 text-center text-red-500">Error loading event details: ${error.message}</div>`;
        }
    },

    async deleteEvent(id) {
        if (!confirm('Are you sure you want to delete this event?')) return;
        try {
            await App.request('events/delete.php', 'DELETE', { id });
            App.showToast('Event deleted');
            Router.navigate('#/dashboard');
        } catch (error) {
            App.showToast('Failed to delete', 'error');
        }
    },

    async registerEvent(id, type = 'registered') {
        const btn = document.getElementById('register-btn');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Processing...';
        }

        try {
            const result = await App.request('events/register.php', 'POST', { event_id: id, type });
            App.showToast(result.message);
            // Reload logic? 
            // Better to just update UI state, but reloading view is easiest
            if (result.status === 'added') {
                if (btn) btn.innerText = type === 'saved' ? 'Saved' : 'Registered';
            } else {
                if (btn) btn.innerText = 'Register Now';
            }
        } catch (error) {
            App.showToast('Action failed', 'error');
        } finally {
            if (btn) btn.disabled = false;
        }
    },

    async initMyEvents() {
        const container = document.getElementById('my-events-container');
        if (!container) return;

        try {
            const response = await App.request('events/my.php');
            const events = response.data;

            if (events.length === 0) {
                container.innerHTML = '<div class="col-span-3 text-center py-10 text-gray-500">You have not registered for any events.</div>';
                return;
            }

            // Reuse Dashboard render if possible, or duplicate logic
            container.innerHTML = events.map(event => this.renderMyEventCard(event)).join('');
        } catch (error) {
            container.innerHTML = '<div class="col-span-3 text-center py-10 text-red-500">Failed to load events.</div>';
        }
    },

    renderMyEventCard(event) {
        // Copied card logic with minimal changes
        const date = new Date(`${event.date}T${event.time}`);
        const dateStr = date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });

        return `
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative border ${event.registration_type === 'saved' ? 'border-yellow-200' : 'border-green-200'}">
                 <div class="absolute top-2 right-2 z-10 bg-white px-2 py-1 rounded-md text-xs font-bold shadow uppercase ${event.registration_type === 'saved' ? 'text-yellow-600' : 'text-green-600'}">
                    ${event.registration_type}
                </div>
                <div class="h-40 bg-gray-200 relative overflow-hidden">
                    ${event.image_path ? `<img src="${event.image_path}" alt="${event.title}" class="w-full h-full object-cover">` : ''}
                </div>
                <div class="p-4">
                     <h3 class="font-bold text-lg truncate">${event.title}</h3>
                     <p class="text-sm text-gray-500">${dateStr} • ${event.location}</p>
                     <a href="#/events/${event.id}" class="block mt-3 text-center w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-1 rounded text-sm font-medium">View Details</a>
                </div>
            </div>
        `;
    },

    async reportEvent(id) {
        const reason = prompt('Please describe the issue with this event:');
        if (!reason) return;

        try {
            await App.request('reports/store.php', 'POST', { event_id: id, reason });
            App.showToast('Event reported to admin');
        } catch (error) {
            App.showToast('Failed to report event', 'error');
        }
    },

    async initEdit(id) {
        const form = document.getElementById('edit-event-form');
        const loader = document.getElementById('loading-indicator');
        if (!form) return;

        try {
            // Load existing data
            const response = await App.request(`events/show.php?id=${id}`);
            const event = response.data;

            // Populate form
            document.getElementById('event-id').value = event.id;
            document.getElementById('title').value = event.title;
            document.getElementById('description').value = event.description;
            document.getElementById('category').value = event.category;
            document.getElementById('organizer').value = event.organizer;
            document.getElementById('date').value = event.date;
            document.getElementById('time').value = event.time; // Format if needed, but time input expects HH:mm
            document.getElementById('location').value = event.location;
            if (event.capacity) document.getElementById('capacity').value = event.capacity;
            if (event.image_path) document.getElementById('image_path').value = event.image_path;
            if (event.is_starpoints) document.getElementById('is_starpoints').checked = true;

            form.classList.remove('hidden');
            loader.classList.add('hidden');
        } catch (error) {
            App.showToast('Failed to load event data', 'error');
            Router.navigate('#/dashboard');
            return;
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerText = 'Updating...';

            const formData = new FormData(form);
            if (!formData.has('is_starpoints')) {
                formData.append('is_starpoints', 0);
            } else {
                formData.set('is_starpoints', 1);
            }

            const data = Object.fromEntries(formData.entries());

            try {
                await App.request('events/update.php', 'POST', data);
                App.showToast('Event updated successfully!');
                Router.navigate(`#/events/${id}`);
            } catch (error) {
                const msg = error.errors ? Object.values(error.errors)[0] : (error.message || 'Failed to update event');
                App.showToast(msg, 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Update Event';
            }
        });
    }
};
