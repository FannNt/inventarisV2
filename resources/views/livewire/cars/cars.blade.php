@php use Carbon\Carbon; @endphp
<div class="min-h-screen bg-white   ">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Cars</h2>
        </div>

        <!-- Filter Alert -->
        @if($expirationFilter)
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="p-1.5 bg-blue-100 rounded-lg">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-blue-900">
                                @switch($expirationFilter)
                                    @case('need_service')
                                        Showing Need Service Cars
                                        @break
                                    @case('warning_service')
                                        Showing Need Service Soon Cars
                                        @break
                                    @case('serviced')
                                        Showing Serviced Cars
                                        @break
                                @endswitch
                            </h3>
                            <p class="text-blue-700 text-sm">Filter is currently active</p>
                        </div>
                    </div>
                    <button wire:click="clearFilters"
                            class="px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors duration-200 flex items-center space-x-1 text-sm font-medium">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>Clear</span>
                    </button>
                </div>
            </div>
        @endif

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4">
                <!-- Search Input -->
                <div class="col-span-1 lg:col-span-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search"
                           class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500"
                           placeholder="Search equipment...">
                </div>

                <div>
                    <select wire:model.live="expirationFilter"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
                        <option value="">All Conditions</option>
                        <option value="need_service">Need service</option>
                        <option value="warning_service">Need Service soon</option>
                        <option value="serviced">serviced</option>
                    </select>
                </div>

                <div>
                    <select wire:model.live="fungsiFilter"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
                        <option value="">All Function</option>
                        <option value="pribadi">Pribadi</option>
                        <option value="ambulance">Ambulance</option>
                    </select>
                </div>
            </div>
        </div>


        <!-- Items Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($cars as $car)
                @php

                    $today = Carbon::now();
                    $threeMonthsFromNow = $today->copy()->addMonths(3);
                    $lastService = $car->current_service;
                    $expiryDate = $lastService ? Carbon::parse($lastService)->addMonths(6) : null;

                    $statusConfig = !$expiryDate ? [
                        'bg' => 'bg-gray-50',
                        'border' => 'border-gray-200',
                        'accent' => 'bg-gray-100',
                        'text' => 'text-gray-600',
                        'status' => 'Service not detected'
                    ] : ($expiryDate->lt($today) ? [
                        'bg' => 'bg-red-50/50',
                        'border' => 'border-red-200',
                        'accent' => 'bg-red-100',
                        'text' => 'text-red-700',
                        'status' => 'Need Service'
                    ] : ($expiryDate->lte($threeMonthsFromNow) ? [
                        'bg' => 'bg-amber-50/50',
                        'border' => 'border-amber-200',
                        'accent' => 'bg-amber-100',
                        'text' => 'text-amber-700',
                        'status' => 'Need Service Soon'
                    ] : [
                        'bg' => 'bg-emerald-50/50',
                        'border' => 'border-emerald-200',
                        'accent' => 'bg-emerald-100',
                        'text' => 'text-emerald-700',
                        'status' => 'Serviced'
                    ]));
                @endphp
                <div class="group relative bg-white border border-gray-200 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 {{ $statusConfig['bg'] }}">
                    <!-- Status Indicator -->
                    <div class="absolute top-4 right-4 z-10">
                        <div class="flex items-center rounded-full {{ $statusConfig['accent'] }} {{ $statusConfig['text'] }}">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium  ">
                                {{ $expiryDate ? Carbon::parse($expiryDate)->format('d M Y') : '-' }}
                            </span>
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium">
                                {{ $statusConfig['status'] }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Equipment Name -->
                        <div class="mb-5 pr-28">
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors duration-200">
                                {{ $car->name }}
                            </h3>
                        </div>

                        <!-- Equipment Details -->
                        <div class="space-y-3">
                            <!-- Fungsi -->
                            <div class="flex items-center space-x-3">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm text-gray-500 font-medium">Fungsi</p>
                                    <p class="text-gray-900 font-medium truncate">{{ $car->fungsi }}</p>
                                </div>
                            </div>

                            <!-- Expiry Date -->
                            <div class="flex items-center space-x-3">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm text-gray-500 font-medium">Tanggal Pajak</p>
                                    @if(!$car->tanggal_pajak)
                                        <p class="text-gray-900 font-medium">No expiry date</p>
                                    @else
                                        <p class="font-medium">
                                            {{ \Carbon\Carbon::parse($car->tanggal_pajak)->format('M d, Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm text-gray-500 font-medium">Last service</p>
                                    @if(!$car->latestService)
                                        <p class="text-gray-900 font-medium">Service data empty</p>
                                    @else
                                        <p class="font-medium">
                                            {{ \Carbon\Carbon::parse($car->current_service)->format('M d, Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Serial Number -->
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 p-2 bg-gray-50 rounded-lg">
                                    <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm text-gray-500 font-medium">Nomor Polisi</p>
                                    <p class="text-gray-900 font-medium font-mono text-sm">{{ $car->nopol ?: 'Not available' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 p-2 bg-gray-50 rounded-lg">
                                    <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm text-gray-500 font-medium">Nomor Kerangka</p>
                                    <p class="text-gray-900 font-medium font-mono text-sm">{{ $car->nopol ?: 'Not available' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="mt-5 pt-5 border-t border-gray-100">
                            <a href="{{ route('cars.show', $car->id) }}"
                               class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-blue-400 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($cars->hasPages())
            <div class="mt-8">
                {{ $cars->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>
