<x-guest-layout subtitle="Sign in to manage and discover events">
    <div class="space-y-6">
        <x-card class="border-none shadow-none p-0">
            <x-card.header class="px-0 pt-0 text-center">
                <x-card.title>Welcome Back</x-card.title>
                <x-card.description>Enter your credentials to access your account</x-card.description>
            </x-card.header>
            <x-card.content class="px-0">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

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
                                autofocus autocomplete="username" placeholder="student@iium.edu.my" />
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
                                autocomplete="current-password" placeholder="Enter your password" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <x-checkbox id="remember_me" name="remember" />
                            <label for="remember_me"
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-muted-foreground">{{ __('Remember me') }}</label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="text-sm font-medium text-primary hover:underline hover:text-primary/80"
                                href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <x-button class="w-full" size="lg">
                        {{ __('Sign In') }}
                    </x-button>

                    <!-- Divider -->
                    <div class="relative py-2">
                        <div class="absolute inset-0 flex items-center">
                            <span class="w-full border-t border-border"></span>
                        </div>
                        <div class="relative flex justify-center text-xs uppercase">
                            <span class="bg-card px-2 text-muted-foreground bg-white">
                                New to IIUM Event Hub?
                            </span>
                        </div>
                    </div>

                    <x-button variant="outline" class="w-full" as="a" href="{{ route('register') }}">
                        Create an Account
                    </x-button>
                </form>
            </x-card.content>
        </x-card>
    </div>
</x-guest-layout>