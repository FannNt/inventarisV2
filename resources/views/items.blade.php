<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Items') }}
        </h2>
    </x-slot>
    <div class="py-1">
        @livewire("items")
    </div>
</x-app-layout>
