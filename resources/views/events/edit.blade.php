<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center gap-4">
            <x-button variant="ghost" size="icon" as="a" href="{{ url()->previous() }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </x-button>
            <div>
                <h1 class="text-2xl font-bold text-foreground">Edit Event</h1>
                <p class="text-muted-foreground mt-1">Update event details</p>
            </div>
        </div>

        <form method="POST" action="{{ route('events.update', $event) }}" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <x-card class="p-6 space-y-6">
                <!-- Picture Upload -->
                <div class="space-y-2" x-data="{ 
                    imagePreview: '{{ $event->image_path ? asset($event->image_path) : '' }}',
                    handleFileChange(event) {
                        const file = event.target.files[0];
                        if (file) {
                            if (file.size > 5 * 1024 * 1024) {
                                alert('Image size should be less than 5MB');
                                return;
                            }
                            const reader = new FileReader();
                            reader.onload = (e) => { this.imagePreview = e.target.result };
                            reader.readAsDataURL(file);
                        }
                    }
                }">
                    <x-label>Picture</x-label>
                    <div
                        class="border-2 border-dashed border-input rounded-lg p-6 text-center hover:bg-accent/50 transition-colors">
                        <template x-if="imagePreview">
                            <div class="space-y-4">
                                <img :src="imagePreview" alt="Event preview"
                                    class="max-h-64 mx-auto rounded-lg object-cover">
                                <x-button type="button" variant="outline"
                                    @click="imagePreview = ''; $refs.fileInput.value = ''">
                                    Remove Image
                                </x-button>
                            </div>
                        </template>
                        <template x-if="!imagePreview">
                            <div class="space-y-3">
                                <div class="flex justify-center">
                                    <div class="bg-muted p-4 rounded-full">
                                        <svg class="h-8 w-8 text-muted-foreground" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <label for="image" class="cursor-pointer">
                                    <div
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-background border border-input rounded-md hover:bg-accent transition-colors text-sm font-medium">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12">
                                            </path>
                                        </svg>
                                        <span>Choose File</span>
                                    </div>
                                    <input x-ref="fileInput" id="image" name="image" type="file" class="hidden"
                                        accept="image/*" @change="handleFileChange">
                                </label>
                                <p class="text-xs text-muted-foreground mt-2">Recommended dimension is 4:3 (1080 x 1320
                                    px)</p>
                            </div>
                        </template>
                    </div>
                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                </div>

                <!-- Title -->
                <div class="space-y-2">
                    <x-label for="title" class="after:content-['*'] after:ml-0.5 after:text-red-500">Title</x-label>
                    <x-input id="title" name="title" :value="old('title', $event->title)"
                        placeholder="Enter event title" />
                    <x-input-error :messages="$errors->get('title')" />
                </div>



                <!-- Category -->
                <div class="space-y-2">
                    <x-label for="category"
                        class="after:content-['*'] after:ml-0.5 after:text-red-500">Category</x-label>
                    <x-select name="category" defaultValue="{{ old('category', $event->category) }}">
                        <x-select.trigger>
                            <x-select.value placeholder="Select category" />
                        </x-select.trigger>
                        <x-select.content>
                            <x-select.item value="Academic">Academic</x-select.item>
                            <x-select.item value="Cultural">Cultural</x-select.item>
                            <x-select.item value="Sports">Sports</x-select.item>
                            <x-select.item value="Religious">Religious</x-select.item>
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
                        placeholder="Enter event description">{{ old('description', $event->description) }}</x-textarea>
                    <x-input-error :messages="$errors->get('description')" />
                </div>

                <!-- Date & Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <x-label for="date" class="after:content-['*'] after:ml-0.5 after:text-red-500">Date</x-label>
                        <x-input type="date" id="date" name="date" :value="old('date', $event->date->format('Y-m-d'))" />
                        <x-input-error :messages="$errors->get('date')" />
                    </div>
                    <div class="space-y-2">
                        <x-label for="time" class="after:content-['*'] after:ml-0.5 after:text-red-500">Time</x-label>
                        <x-input type="time" id="time" name="time" :value="old('time', \Carbon\Carbon::parse($event->time)->format('H:i'))" />
                        <x-input-error :messages="$errors->get('time')" />
                    </div>
                </div>

                <!-- Location -->
                <div class="space-y-2">
                    <x-label for="location"
                        class="after:content-['*'] after:ml-0.5 after:text-red-500">Location</x-label>
                    <x-input id="location" name="location" :value="old('location', $event->location)"
                        placeholder="Enter event location" />
                    <x-input-error :messages="$errors->get('location')" />
                </div>

                <!-- Organizer -->
                <div class="space-y-2">
                    <x-label for="organizer"
                        class="after:content-['*'] after:ml-0.5 after:text-red-500">Organizer</x-label>
                    <x-input id="organizer" name="organizer" :value="old('organizer', $event->organizer)"
                        placeholder="Enter organizer name" />
                    <x-input-error :messages="$errors->get('organizer')" />
                </div>

                <!-- Starpoints -->
                <div class="space-y-3">
                    <x-label class="after:content-['*'] after:ml-0.5 after:text-red-500">Does this event give
                        Starpoints?</x-label>
                    <div class="flex flex-col gap-2 mt-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="is_starpoints" value="1" class="form-radio text-primary w-4 h-4"
                                @checked(old('is_starpoints', $event->is_starpoints) == 1)>
                            <span class="ml-2 text-foreground">Yes</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="is_starpoints" value="0" class="form-radio text-primary w-4 h-4"
                                @checked(old('is_starpoints', $event->is_starpoints) == 0)>
                            <span class="ml-2 text-foreground">No</span>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('is_starpoints')" />
                </div>

                <!-- Participation Link -->
                <div class="space-y-2">
                    <x-label for="participation_link">Participation Link</x-label>
                    <x-input type="url" id="participation_link" name="participation_link"
                        :value="old('participation_link', $event->participation_link)"
                        placeholder="https://example.com/event-link" />
                </div>

                <!-- Tags -->
                <div class="space-y-4" x-data="{
                    tags: {{ json_encode($event->tags->pluck('name')->toArray()) }},
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

                    <x-label>Tags</x-label>

                    <!-- Activity Tags -->
                    <div class="space-y-2">
                        <p class="text-xs text-muted-foreground">Activity Tags:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['free food', 'talks', 'spiritual', 'health', 'exhibition', 'sport', 'paid', 'free'] as $tag)
                                <button type="button" @click="addTag('{{ $tag }}')"
                                    :class="tags.includes('{{ $tag }}') ? 'bg-primary text-primary-foreground' : 'bg-background border border-input hover:bg-accent text-foreground'"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium h-8 px-3 transition-colors">
                                    {{ $tag }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Kulliyyah Tags -->
                    <div class="space-y-2">
                        <p class="text-xs text-muted-foreground">Kulliyyah Tags:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['AHAS KIRKHS', 'AIKOL', 'KAED', 'KENMS', 'KOED', 'KOE', 'KICT'] as $tag)
                                <button type="button" @click="addTag('{{ $tag }}')"
                                    :class="tags.includes('{{ $tag }}') ? 'bg-primary text-primary-foreground' : 'bg-background border border-input hover:bg-accent text-foreground'"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium h-8 px-3 transition-colors">
                                    {{ $tag }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Add Custom Tag -->
                    <div class="flex gap-2">
                        <x-input x-model="customTag" @keydown.enter.prevent="addCustom"
                            placeholder="Enter custom tag" />
                        <x-button type="button" variant="outline" @click="addCustom">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add
                        </x-button>
                    </div>

                    <!-- Selected Tags -->
                    <template x-if="tags.length > 0">
                        <div class="space-y-2">
                            <p class="text-xs text-muted-foreground">Selected Tags:</p>
                            <div class="flex flex-wrap gap-2 p-3 bg-muted rounded-lg">
                                <template x-for="tag in tags" :key="tag">
                                    <div
                                        class="inline-flex items-center gap-1 px-3 py-1 bg-primary text-primary-foreground rounded-full text-sm">
                                        <span x-text="tag"></span>
                                        <button type="button" @click="removeTag(tag)"
                                            class="hover:bg-primary/80 rounded-full p-0.5">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </x-card>

            <div class="flex gap-3 pt-4">
                <x-button type="submit" class="flex-1">Save Changes</x-button>
                <x-button variant="outline" as="a" href="{{ url()->previous() }}" class="flex-1">Cancel</x-button>
            </div>
        </form>
    </div>
</x-app-layout>