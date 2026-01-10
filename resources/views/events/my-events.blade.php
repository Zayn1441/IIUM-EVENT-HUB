<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="space-y-6" x-data="{ deleteAction: '' }">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold tracking-tight text-foreground">My Events</h1>
                <x-button as="a" href="{{ route('events.create') }}">Create Event</x-button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($events as $event)
                    @php
                        $percentage = $event->capacity > 0 ? ($event->registrations_count / $event->capacity) * 100 : 0;
                        $status = 'upcoming';
                        if (now()->greaterThan(\Carbon\Carbon::parse($event->date->format('Y-m-d') . ' ' . $event->time))) {
                            $status = 'completed';
                        } elseif (now()->isSameDay($event->date)) {
                            $status = 'ongoing';
                        }
                    @endphp
                    <x-event-card :event="$event">
                        <x-slot:actions>
                            <x-button variant="outline" size="sm" class="" as="a" href="{{ route('events.show', $event) }}">
                                View Details
                            </x-button>
                            <x-button variant="outline" size="sm" class="gap-2" as="a"
                                href="{{ route('events.edit', $event) }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                                Edit
                            </x-button>
                            <x-button variant="outline" size="sm"
                                class="text-destructive hover:text-destructive hover:bg-destructive/10 gap-2"
                                @click="deleteAction = '{{ route('events.destroy', $event) }}'; $dispatch('open-modal', 'delete-confirmation')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                Delete
                            </x-button>
                        </x-slot:actions>
                    </x-event-card>
                @empty
                    <div class="col-span-full">
                        <x-card class="bg-muted/50 border-dashed">
                            <x-card.content
                                class="flex flex-col items-center justify-center py-12 text-center text-muted-foreground">
                                <svg class="h-12 w-12 mb-4 opacity-20" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                <p class="text-lg font-medium">No events created yet</p>
                                <p class="mb-4">Get started by creating your first event</p>
                                <x-button as="a" href="{{ route('events.create') }}">Create Event</x-button>
                            </x-card.content>
                        </x-card>
                    </div>
                @endforelse
            </div>

            <x-modal name="delete-confirmation" title="Delete Event"
                description="Are you sure you want to delete this event? This action cannot be undone."
                confirmText="Yes, Delete" cancelText="No, Cancel" confirmStyle="danger">
                <form method="POST" :action="deleteAction" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" variant="destructive">
                        Yes, Delete
                    </x-button>
                </form>
            </x-modal>
        </div>
    </div>
</x-app-layout>