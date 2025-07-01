<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            <!-- Logo -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" wire:navigate>
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-14">
                </a>
            </div>

            <!-- Desktop Nav -->
            <div class="hidden sm:flex space-x-10">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                    Dashboard
                </x-nav-link>
                <x-nav-link :href="route('items')" :active="request()->routeIs('items')" wire:navigate>
                    Items
                </x-nav-link>
            </div>

            <!-- Hamburger Icon -->
            <div class="sm:hidden">
                <button @click="open = !open"
                        class="p-2 rounded-md text-gray-600 hover:text-blue-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Nav -->
        <div x-show="open" class="sm:hidden flex flex-col mt-4 space-y-2">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                Dashboard
            </x-nav-link>
            <x-nav-link :href="route('items')" :active="request()->routeIs('items')" wire:navigate>
                Items
            </x-nav-link>
        </div>
    </div>
</nav>
