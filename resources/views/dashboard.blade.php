<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    @livewire("dashboard")
    <div class="py-12">
    </div>
</x-app-layout>
