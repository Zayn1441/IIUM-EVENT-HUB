<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-background text-foreground">
    <x-sidebar.provider>
        <x-sidebar>
            <x-sidebar.header>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-2 py-1 hover:opacity-80 transition-opacity">
                    <div
                        class="flex aspect-square size-8 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                        <img src="{{ asset('images/logo.png') }}" alt="IIUM Event Hub"
                            class="w-full h-full object-contain">
                    </div>
                    <div class="grid flex-1 text-left text-sm leading-tight">
                        <span class="truncate font-semibold">IIUM</span>
                        <span class="truncate text-xs text-muted-foreground">Event Hub</span>
                    </div>
                </a>
            </x-sidebar.header>

            <x-sidebar.content>
                <x-sidebar.menu>
                    <x-sidebar.menu-item>
                        <x-sidebar.menu-button :active="request()->routeIs('dashboard')" as="a"
                            href="{{ route('dashboard') }}">
                            <svg class="size-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect width="7" height="9" x="3" y="3" rx="1" />
                                <rect width="7" height="5" x="14" y="3" rx="1" />
                                <rect width="7" height="9" x="14" y="12" rx="1" />
                                <rect width="7" height="5" x="3" y="16" rx="1" />
                            </svg>
                            <span>Dashboard</span>
                        </x-sidebar.menu-button>
                    </x-sidebar.menu-item>
                    <x-sidebar.menu-item>
                        <x-sidebar.menu-button :active="request()->routeIs('events.index')" as="a"
                            href="{{ route('events.index') }}">
                            <svg class="size-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                            <span>All Events</span>
                        </x-sidebar.menu-button>
                    </x-sidebar.menu-item>

                    <div class="px-2 py-2 mt-4">
                        <h4 class="text-xs font-semibold text-muted-foreground uppercase tracking-wider mb-2 px-2">Mine
                        </h4>
                        <x-sidebar.menu>
                            <x-sidebar.menu-item>
                                <x-sidebar.menu-button :active="request()->routeIs('events.saved-events')" as="a"
                                    href="{{ route('events.saved-events') }}">
                                    <svg class="size-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z" />
                                    </svg>
                                    <span>Saved Events</span>
                                </x-sidebar.menu-button>
                            </x-sidebar.menu-item>
                            <x-sidebar.menu-item>
                                <x-sidebar.menu-button :active="request()->routeIs('events.my-events')" as="a"
                                    href="{{ route('events.my-events') }}">
                                    <svg class="size-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                    <span>My Events</span>
                                </x-sidebar.menu-button>
                            </x-sidebar.menu-item>
                            <x-sidebar.menu-item>
                                <x-sidebar.menu-button :active="request()->routeIs('events.calendar')" as="a"
                                    href="{{ route('events.calendar') }}">
                                    <svg class="size-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                        <line x1="16" x2="16" y1="2" y2="6" />
                                        <line x1="8" x2="8" y1="2" y2="6" />
                                        <line x1="3" x2="21" y1="10" y2="10" />
                                    </svg>
                                    <span>Calendar</span>
                                </x-sidebar.menu-button>
                            </x-sidebar.menu-item>
                        </x-sidebar.menu>
                    </div>

                    <div class="px-2 py-2 mt-4">
                        <x-sidebar.menu-item>
                            <x-sidebar.menu-button :active="request()->routeIs('events.create')" as="a"
                                href="{{ route('events.create') }}">
                                <svg class="size-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                    <path d="M12 5v14" />
                                </svg>
                                <span>Create Event</span>
                            </x-sidebar.menu-button>
                        </x-sidebar.menu-item>
                    </div>

                    <div class="px-2 py-2 mt-4">
                        <x-sidebar.menu-item>
                            <x-sidebar.menu-button :active="request()->routeIs('notices.index')" as="a"
                                href="{{ route('notices.index') }}">
                                <svg class="size-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                                </svg>
                                <span>Notices</span>
                                @if(isset($noticesCount) && $noticesCount > 0)
                                    <span class="ml-auto flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-600 text-[10px] font-medium text-white">
                                        {{ $noticesCount }}
                                    </span>
                                @endif
                            </x-sidebar.menu-button>
                        </x-sidebar.menu-item>
                    </div>

                    @if(auth()->user()->is_admin)
                        <div class="px-2 py-2 mt-4">
                            <h4 class="text-xs font-semibold text-muted-foreground uppercase tracking-wider mb-2 px-2">Admin</h4>
                            <x-sidebar.menu-item>
                                <x-sidebar.menu-button :active="request()->routeIs('admin.reports.index')" as="a"
                                    href="{{ route('admin.reports.index') }}">
                                    <svg class="size-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                    </svg>
                                    <span>Reported Events</span>
                                    @if(isset($reportsCount) && $reportsCount > 0)
                                        <span class="ml-auto flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-600 text-[10px] font-medium text-white">
                                            {{ $reportsCount }}
                                        </span>
                                    @endif
                                </x-sidebar.menu-button>
                            </x-sidebar.menu-item>
                        </div>
                    @endif

                </x-sidebar.menu>
            </x-sidebar.content>

            <x-sidebar.footer>
                <div class="flex items-center gap-3 px-2 py-2">
                    <div class="h-8 w-8 rounded-full bg-muted flex items-center justify-center text-primary font-bold">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="grid flex-1 text-left text-sm leading-tight">
                        <span class="truncate font-semibold">{{ Auth::user()->name }}</span>
                        <span class="truncate text-xs text-muted-foreground">{{ Auth::user()->email }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <div class="px-2 pb-2">
                        <x-sidebar.menu-button as="button" type="submit"
                            class="w-full text-destructive hover:text-destructive hover:bg-destructive/10">
                            <svg class="size-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" x2="9" y1="12" y2="12" />
                            </svg>
                            <span>Log out</span>
                        </x-sidebar.menu-button>
                    </div>
                </form>
            </x-sidebar.footer>
        </x-sidebar>

        <x-sidebar.inset>
            <header class="flex h-16 shrink-0 items-center gap-2 border-b bg-background px-4">
                <x-sidebar.trigger class="-ml-1" />

            </header>

            <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
                <main>
                    {{ $slot }}
                </main>
            </div>
        </x-sidebar.inset>
    </x-sidebar.provider>
    <x-toaster />
    @if(session('success'))
        <script>
            document.addEventListener('alpine:init', () => {
                setTimeout(() => {
                    window.dispatchEvent(new CustomEvent('notify', {
                        detail: {
                            title: 'Success',
                            description: '{{ session('success') }}',
                            type: 'success'
                        }
                    }));
                }, 300);
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            document.addEventListener('alpine:init', () => {
                setTimeout(() => {
                    window.dispatchEvent(new CustomEvent('notify', {
                        detail: {
                            title: 'Error',
                            description: '{{ session('error') }}',
                            type: 'error'
                        }
                    }));
                }, 300);
            });
        </script>
    @endif
    <x-modal name="no-link-modal" focusable>
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 mb-4">
                <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h2 class="text-lg font-medium text-gray-900 mb-2">
                No Registration Link
            </h2>
            <p class="text-sm text-gray-500 mb-6">
                The organizer has not provided a participation link for this event. Please contact the organizer
                directly or check the venue details.
            </p>
            <div class="flex justify-center">
                <x-secondary-button x-on:click="$dispatch('close-modal', 'no-link-modal')">
                    Close
                </x-secondary-button>
            </div>
        </div>
    </x-modal>

    <x-modal name="event-passed-modal" focusable>
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-4">
                <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-lg font-medium text-gray-900 mb-2">
                Event Has Passed
            </h2>
            <p class="text-sm text-gray-500 mb-6">
                This event has already taken place and registration is closed.
            </p>
            <div class="flex justify-center">
                <x-secondary-button x-on:click="$dispatch('close-modal', 'event-passed-modal')">
                    Close
                </x-secondary-button>
            </div>
        </div>
    </x-modal>

    @stack('scripts')
</body>

</html>