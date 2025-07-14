<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $car->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">Vehicle Details & Service History</p>
            </div>
            <a href="{{ route('cars') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Items
            </a>
        </div>
    </x-slot>

    @livewire('service-detail-modal',['carId' => $car->id])
</x-app-layout>
