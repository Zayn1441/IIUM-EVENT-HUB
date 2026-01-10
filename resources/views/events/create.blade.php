<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center gap-4">
            <x-button variant="outline" size="icon" as="a" href="{{ url()->previous() }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </x-button>
            <h1 class="text-2xl font-bold text-foreground">Create Event</h1>
        </div>

        <p class="text-muted-foreground mb-8 border-b pb-4">Fill in the details to create a new event</p>

        <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Picture Upload -->
            <x-card class="p-6" x-data="{
                preview: null,
                handleFileCheck(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.preview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }">
                <x-label class="mb-4 block">Picture</x-label>

                <!-- Preview Area -->
                <div x-show="preview" class="mb-4 relative h-64 w-full rounded-lg overflow-hidden"
                    style="display: none;">
                    <img :src="preview" class="w-full h-full object-cover">
                    <button type="button" @click="preview = null; document.getElementById('image').value = ''"
                        class="absolute top-2 right-2 bg-destructive text-white p-2 rounded-full hover:bg-destructive/90 transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Upload Area (Hidden when preview is shown) -->
                <div x-show="!preview"
                    class="border-2 border-dashed border-input rounded-lg p-8 flex flex-col items-center justify-center hover:bg-accent/50 transition-colors">
                    <div
                        class="w-12 h-12 bg-muted rounded-full flex items-center justify-center mb-3 text-muted-foreground">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <label for="image" class="cursor-pointer">
                        <span
                            class="bg-background border border-input text-foreground px-4 py-2 rounded-lg text-sm font-medium hover:bg-accent shadow-sm">Choose
                            File</span>
                        <input id="image" name="image" type="file" class="sr-only" accept="image/*"
                            @change="handleFileCheck">
                    </label>
                    <p class="text-xs text-muted-foreground mt-3 text-center">Recommended dimension is 4:3 (1080 x 1320
                        px)</p>
                </div>
                <x-input-error :messages="$errors->get('image')" class="mt-2" />
            </x-card>

            <x-card class="p-6 space-y-6">
                <!-- Title -->
                <div class="space-y-2">
                    <x-label for="title" class="after:content-['*'] after:ml-0.5 after:text-red-500">Title</x-label>
                    <x-input id="title" name="title" :value="old('title')" placeholder="Enter event title" />
                    <x-input-error :messages="$errors->get('title')" />
                </div>



                <!-- Category -->
                <div class="space-y-2">
                    <x-label for="category"
                        class="after:content-['*'] after:ml-0.5 after:text-red-500">Category</x-label>
                    <x-select name="category" defaultValue="{{ old('category', 'Academic') }}">
                        <x-select.trigger>
                            <x-select.value placeholder="Select category" />
                        </x-select.trigger>
                        <x-select.content>
                            <x-select.item value="Academic">Academic</x-select.item>
                            <x-select.item value="Cultural">Cultural</x-select.item>
                            <x-select.item value="Sports">Sports</x-select.item>
                            <x-select.item value="Spiritual">Spiritual</x-select.item>
                            <x-select.item value="Social">Social</x-select.item>
                            <x-select.item value="Workshop">Workshop</x-select.item>
                            <x-select.item value="Market">Market</x-select.item>
                            <x-select.item value="Community">Community</x-select.item>
                        </x-select.content>
                    </x-select>
                    <x-input-error :messages="$errors->get('category')" />
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <x-label for="description"
                        class="after:content-['*'] after:ml-0.5 after:text-red-500">Description</x-label>
                    <x-textarea id="description" name="description" rows="4"
                        placeholder="Enter event description">{{ old('description') }}</x-textarea>
                    <x-input-error :messages="$errors->get('description')" />
                </div>

                <!-- Date & Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <x-label for="date" class="after:content-['*'] after:ml-0.5 after:text-red-500">Date</x-label>
                        <x-input type="date" id="date" name="date" :value="old('date')" />
                        <x-input-error :messages="$errors->get('date')" />
                    </div>
                    <div class="space-y-2">
                        <x-label for="time" class="after:content-['*'] after:ml-0.5 after:text-red-500">Time</x-label>
                        <x-input type="time" id="time" name="time" :value="old('time')" />
                        <x-input-error :messages="$errors->get('time')" />
                    </div>
                </div>

                <!-- Location -->
                <div class="space-y-2">
                    <x-label for="location"
                        class="after:content-['*'] after:ml-0.5 after:text-red-500">Location</x-label>
                    <x-input id="location" name="location" :value="old('location')"
                        placeholder="Enter event location" />
                    <x-input-error :messages="$errors->get('location')" />
                </div>

                <!-- Organizer -->
                <div class="space-y-2">
                    <x-label for="organizer"
                        class="after:content-['*'] after:ml-0.5 after:text-red-500">Organizer</x-label>
                    <x-input id="organizer" name="organizer" :value="old('organizer')"
                        placeholder="Enter organizer name" />
                    <x-input-error :messages="$errors->get('organizer')" />
                </div>

                <!-- Starpoints (Radio Group) -->
                <div class="space-y-2">
                    <x-label class="after:content-['*'] after:ml-0.5 after:text-red-500">Does this event give
                        Starpoints?</x-label>
                    <div class="flex flex-col gap-2 mt-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="is_starpoints" value="1" class="form-radio text-primary w-4 h-4"
                                @checked(old('is_starpoints', '0') == 1)>
                            <span class="ml-2 text-foreground">Yes</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="is_starpoints" value="0" class="form-radio text-primary w-4 h-4"
                                @checked(old('is_starpoints', '0') == 0)>
                            <span class="ml-2 text-foreground">No</span>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('is_starpoints')" />
                </div>

                <!-- Link -->
                <div class="space-y-2">
                    <x-label for="participation_link">Participation Link</x-label>
                    <x-input type="url" id="participation_link" name="participation_link"
                        :value="old('participation_link')" placeholder="https://example.com/event-link" />
                </div>

                <div class="space-y-6" x-data="{
                    tags: [],
                    customTag: '',
                    addTag(tag) {
                        if (tag && !this.tags.includes(tag)) {
                            this.tags.push(tag);
                        }
                    },
                    removeTag(tag) {
                        this.tags = this.tags.filter(t => t !== tag);
                    },
                    addCustom() {
                        if (this.customTag.trim()) {
                            this.addTag(this.customTag.trim());
                            this.customTag = '';
                        }
                    }
                }">
                    <input type="hidden" name="tags" id="tags-input" :value="tags.join(',')">

                    <x-label class="text-base font-semibold">Tags</x-label>

                    <!-- Activity Tags -->
                    <div class="space-y-2">
                        <p class="text-sm text-muted-foreground">Activity Tags:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['free food', 'talks', 'spiritual', 'health', 'exhibition', 'sport', 'paid', 'free'] as $tag)
                                <button type="button" @click="addTag('{{ $tag }}')"
                                    class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                                    {{ $tag }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Kulliyyah Tags -->
                    <div class="space-y-2">
                        <p class="text-sm text-muted-foreground">Kulliyyah Tags:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['AHAS KIRKHS', 'AIKOL', 'KAED', 'KENMS', 'KOED', 'KOE', 'KICT'] as $tag)
                                <button type="button" @click="addTag('{{ $tag }}')"
                                    class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-primary-foreground h-9 px-4 py-2 bg-black hover:bg-black/90">
                                    {{ $tag }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Add Custom Tag -->
                    <div class="space-y-2">
                        <p class="text-sm text-muted-foreground">Add Custom Tag:</p>
                        <div class="flex gap-2">
                            <x-input x-model="customTag" @keydown.enter.prevent="addCustom" placeholder="custom tags" />
                            <x-button type="button" variant="secondary" @click="addCustom" class="gap-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add
                            </x-button>
                        </div>
                    </div>

                    <!-- Selected Tags -->
                    <div class="space-y-2" x-show="tags.length > 0">
                        <p class="text-sm text-muted-foreground">Selected Tags:</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="tag in tags" :key="tag">
                                <div
                                    class="inline-flex items-center gap-1 rounded-full border px-3 py-1 text-sm font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-blue-600 text-white hover:bg-blue-700">
                                    <span x-text="tag"></span>
                                    <button type="button" @click="removeTag(tag)"
                                        class="ml-1 ring-offset-background rounded-full outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 hover:bg-white/20">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </x-card>

            <div class="flex items-center justify-end space-x-4 pt-4">
                <x-button variant="outline" as="a" href="{{ route('dashboard') }}">Cancel</x-button>
                <x-button type="submit">Create Event</x-button>
            </div>
        </form>
    </div>
</x-app-layout>