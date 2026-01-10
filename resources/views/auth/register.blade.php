<x-guest-layout subtitle="Create your account to get started">
    <div class="space-y-6">
        <x-card class="border-none shadow-none p-0">
            <x-card.header class="px-0 pt-0 text-center">
                <x-card.title>Create Account</x-card.title>
                <x-card.description>Fill in your details to create a new account</x-card.description>
            </x-card.header>
            <x-card.content class="px-0">
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div class="space-y-2">
                        <x-label for="name" value="{{ __('Full Name') }}" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </div>
                            <x-input id="name" class="pl-10" type="text" name="name" :value="old('name')" required
                                autofocus autocomplete="name" placeholder="Enter your full name" />
                        </div>
                        <x-input-error :messages="$errors->get('name')" />
                    </div>

                    <!-- Student ID -->
                    <div class="space-y-2">
                        <x-label for="student_id" value="{{ __('Student ID') }}" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="18" height="18" x="3" y="3" rx="2" />
                                    <path d="M10 3v18" />
                                    <path d="M3 10h18" />
                                    <path d="M3 16h18" />
                                </svg>
                            </div>
                            <x-input id="student_id" class="pl-10" type="text" name="student_id"
                                :value="old('student_id')" required autocomplete="off" placeholder="e.g., 2012345" />
                        </div>
                        <x-input-error :messages="$errors->get('student_id')" />
                    </div>

                    <!-- Email Address -->
                    <div class="space-y-2">
                        <x-label for="email" value="{{ __('Email Address') }}" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="16" x="2" y="4" rx="2" />
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                                </svg>
                            </div>
                            <x-input id="email" class="pl-10" type="email" name="email" :value="old('email')" required
                                autocomplete="username" placeholder="student@iium.edu.my" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <x-label for="password" value="{{ __('Password') }}" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                            </div>
                            <x-input id="password" class="pl-10" type="password" name="password" required
                                autocomplete="new-password" placeholder="Create a password" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                    <path d="m15 15-2 2-2-2" />
                                </svg>
                            </div>
                            <x-input id="password_confirmation" class="pl-10" type="password"
                                name="password_confirmation" required autocomplete="new-password"
                                placeholder="Re-enter your password" />
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" />
                    </div>

                    <x-button class="w-full" size="lg">
                        {{ __('Create Account') }}
                    </x-button>

                    <!-- Divider -->
                    <div class="relative py-2">
                        <div class="absolute inset-0 flex items-center">
                            <span class="w-full border-t border-border"></span>
                        </div>
                        <div class="relative flex justify-center text-xs uppercase">
                            <span class="bg-card px-2 text-muted-foreground bg-white">
                                Already have an account?
                            </span>
                        </div>
                    </div>

                    <x-button variant="outline" class="w-full" as="a" href="{{ route('login') }}">
                        Sign In Instead
                    </x-button>
                </form>
            </x-card.content>
        </x-card>
    </div>
</x-guest-layout>