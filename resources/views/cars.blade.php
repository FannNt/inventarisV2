<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl leading-tight">
                {{ __('Cars') }}
            </h2>
            @auth()
                @if(auth()->user()->hasAnyRole(['superadmin','admin','cars_management']))
                    <a href="/admin/cars" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200 shadow-sm">
                        Admin
                    </a>
                @endif
            @endauth
        </div>

    </x-slot>
    <div class="py-1">
        @livewire("cars")
    </div>
</x-app-layout>
