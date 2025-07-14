<div class="container mx-auto px-4 py-8"
     x-data="{
         activeTab: @if($isAdmin && $canViewItems && $canViewCars) 'items' @elseif($canViewCars) 'cars' @else 'items' @endif
     }">
    @auth()
        @if($canViewItems || $canViewCars)

            <!-- Dashboard Navigation Tabs (for admins who can see both) -->
            @if($isAdmin && $canViewItems && $canViewCars)
                <div class="mb-8">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8">
                            <button
                                @click="activeTab = 'items'"
                                :class="{'border-blue-500 text-blue-600': activeTab === 'items', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'items'}"
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                            >
                                Calibration Items
                            </button>
                            <button
                                @click="activeTab = 'cars'"
                                :class="{'border-blue-500 text-blue-600': activeTab === 'cars', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'cars'}"
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                            >
                                Cars Service
                            </button>
                        </nav>
                    </div>
                </div>
            @endif

            <!-- Items Dashboard -->
            @if($canViewItems)
                <div
                    @if($isAdmin && $canViewItems && $canViewCars)
                        x-show="activeTab === 'items'"
                    @endif
                >
                    <!-- Items Overview Statistics -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold">Calibration Items Overview</h2>
                            <div class="text-sm text-gray-500">
                                Last updated: {{ now()->format('d M Y, H:i') }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="bg-white rounded-lg shadow p-6">
                                <h3 class="text-gray-500 text-sm font-medium">Total Items</h3>
                                <p class="text-3xl font-bold">{{ $totalItems }}</p>
                            </div>
                            <div class="bg-red-50 rounded-lg shadow p-6">
                                <h3 class="text-red-500 text-sm font-medium">Expired</h3>
                                <p class="text-3xl font-bold text-red-600">{{ $expiredItems->count() }}</p>
                            </div>
                            <div class="bg-yellow-50 rounded-lg shadow p-6">
                                <h3 class="text-yellow-500 text-sm font-medium">Expiring Soon</h3>
                                <p class="text-3xl font-bold text-yellow-600">{{ $expiringSoonItems->count() }}</p>
                            </div>
                            <div class="bg-green-50 rounded-lg shadow p-6">
                                <h3 class="text-green-500 text-sm font-medium">Valid</h3>
                                <p class="text-3xl font-bold text-green-600">{{ $validItems->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Items Status Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Expired Items -->
                        <a href="{{ route('items', ['filter' => 'expired']) }}"
                           class="block transition-transform duration-200 hover:scale-105">
                            <div class="bg-red-50 border border-red-200 rounded-lg shadow-lg overflow-hidden h-full">
                                <div class="bg-red-500 text-white p-4">
                                    <h3 class="text-lg font-semibold">Expired Items</h3>
                                    <p class="text-2xl font-bold">{{ $expiredItems->count() }}</p>
                                </div>
                                <div class="p-4 max-h-64 overflow-y-auto">
                                    @foreach($expiredItems->take(5) as $item)
                                        <div class="mb-3 last:mb-0 bg-white p-3 rounded shadow">
                                            <h4 class="font-semibold">{{ $item->name }}</h4>
                                            <p class="text-sm text-red-600">
                                                Expired: {{ \Carbon\Carbon::parse($item->current_expired)->format('d M Y') }}
                                            </p>
                                        </div>
                                    @endforeach
                                    @if($expiredItems->count() > 5)
                                        <div class="text-center mt-3 text-red-600 font-medium">
                                            View {{ $expiredItems->count() - 5 }} more items →
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>

                        <!-- Expiring Soon Items -->
                        <a href="{{ route('items', ['filter' => 'expiring_soon']) }}"
                           class="block transition-transform duration-200 hover:scale-105">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg shadow-lg overflow-hidden h-full">
                                <div class="bg-yellow-500 text-white p-4">
                                    <h3 class="text-lg font-semibold">Expiring Soon</h3>
                                    <p class="text-2xl font-bold">{{ $expiringSoonItems->count() }}</p>
                                </div>
                                <div class="p-4 max-h-64 overflow-y-auto">
                                    @foreach($expiringSoonItems->take(5) as $item)
                                        <div class="mb-3 last:mb-0 bg-white p-3 rounded shadow">
                                            <h4 class="font-semibold">{{ $item->name }}</h4>
                                            <p class="text-sm text-yellow-600">
                                                Expires: {{ \Carbon\Carbon::parse($item->current_expired)->format('d M Y') }}
                                            </p>
                                        </div>
                                    @endforeach
                                    @if($expiringSoonItems->count() > 5)
                                        <div class="text-center mt-3 text-yellow-600 font-medium">
                                            View {{ $expiringSoonItems->count() - 5 }} more items →
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>

                        <!-- Valid Items -->
                        <a href="{{ route('items', ['filter' => 'valid']) }}"
                           class="block transition-transform duration-200 hover:scale-105">
                            <div class="bg-white border rounded-lg shadow-lg overflow-hidden h-full">
                                <div class="bg-green-500 text-white p-4">
                                    <h3 class="text-lg font-semibold">Valid Items</h3>
                                    <p class="text-2xl font-bold">{{ $validItems->count() }}</p>
                                </div>
                                <div class="p-4 max-h-64 overflow-y-auto">
                                    @foreach($validItems->take(5) as $item)
                                        <div class="mb-3 last:mb-0 bg-gray-50 p-3 rounded shadow">
                                            <h4 class="font-semibold">{{ $item->name }}</h4>
                                            @if($item->current_expired)
                                                <p class="text-sm text-gray-600">
                                                    Expires: {{ \Carbon\Carbon::parse($item->current_expired)->format('d M Y') }}
                                                </p>
                                            @else
                                                <p class="text-sm text-gray-600">No expiration date</p>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($validItems->count() > 5)
                                        <div class="text-center mt-3 text-gray-600 font-medium">
                                            View {{ $validItems->count() - 5 }} more items →
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Cars Dashboard -->
            @if($canViewCars)
                <div
                    @if($isAdmin && $canViewItems && $canViewCars)
                        x-show="activeTab === 'cars'"
                    @endif
                >
                    <!-- Cars Overview Statistics -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold">Cars Service Overview</h2>
                            <div class="text-sm text-gray-500">
                                Last updated: {{ now()->format('d M Y, H:i') }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="bg-white rounded-lg shadow p-6">
                                <h3 class="text-gray-500 text-sm font-medium">Total Cars</h3>
                                <p class="text-3xl font-bold">{{ $totalCars }}</p>
                            </div>
                            <div class="bg-red-50 rounded-lg shadow p-6">
                                <h3 class="text-red-500 text-sm font-medium">Overdue Service</h3>
                                <p class="text-3xl font-bold text-red-600">{{ $carsOverdue->count() }}</p>
                            </div>
                            <div class="bg-yellow-50 rounded-lg shadow p-6">
                                <h3 class="text-yellow-500 text-sm font-medium">Service Due Soon</h3>
                                <p class="text-3xl font-bold text-yellow-600">{{ $carsNeedingService->count() }}</p>
                            </div>
                            <div class="bg-green-50 rounded-lg shadow p-6">
                                <h3 class="text-green-500 text-sm font-medium">Recently Serviced</h3>
                                <p class="text-3xl font-bold text-green-600">{{ $carsRecentlyServiced->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Cars Status Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Overdue Service -->
                        <a href="{{ route('cars', ['filter' => 'need_service']) }}"
                           class="block transition-transform duration-200 hover:scale-105">
                            <div class="bg-red-50 border border-red-200 rounded-lg shadow-lg overflow-hidden h-full">
                                <div class="bg-red-500 text-white p-4">
                                    <h3 class="text-lg font-semibold">Overdue Service</h3>
                                    <p class="text-2xl font-bold">{{ $carsOverdue->count() }}</p>
                                </div>
                                <div class="p-4 max-h-64 overflow-y-auto">
                                    @foreach($carsOverdue->take(5) as $car)
                                        <div class="mb-3 last:mb-0 bg-white p-3 rounded shadow">
                                            <h4 class="font-semibold">{{ $car->name ?? $car->license_plate }}</h4>
                                            <p class="text-sm text-red-600">
                                                Due: {{ \Carbon\Carbon::parse($car->next_service_date)->format('d M Y') }}
                                            </p>
                                        </div>
                                    @endforeach
                                    @if($carsOverdue->count() > 5)
                                        <div class="text-center mt-3 text-red-600 font-medium">
                                            View {{ $carsOverdue->count() - 5 }} more cars →
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>

                        <!-- Service Due Soon -->
                        <a href="{{ route('cars', ['filter' => 'warning_service']) }}"
                           class="block transition-transform duration-200 hover:scale-105">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg shadow-lg overflow-hidden h-full">
                                <div class="bg-yellow-500 text-white p-4">
                                    <h3 class="text-lg font-semibold">Service Due Soon</h3>
                                    <p class="text-2xl font-bold">{{ $carsNeedingService->count() }}</p>
                                </div>
                                <div class="p-4 max-h-64 overflow-y-auto">
                                    @foreach($carsNeedingService->take(5) as $car)
                                        <div class="mb-3 last:mb-0 bg-white p-3 rounded shadow">
                                            <h4 class="font-semibold">{{ $car->name ?? $car->license_plate }}</h4>
                                            <p class="text-sm text-yellow-600">
                                                Due: {{ \Carbon\Carbon::parse($car->next_service_date)->format('d M Y') }}
                                            </p>
                                        </div>
                                    @endforeach
                                    @if($carsNeedingService->count() > 5)
                                        <div class="text-center mt-3 text-yellow-600 font-medium">
                                            View {{ $carsNeedingService->count() - 5 }} more cars →
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>

                        <!-- Recently Serviced -->
                        <a href="{{ route('cars', ['filter' => 'serviced']) }}"
                           class="block transition-transform duration-200 hover:scale-105">
                            <div class="bg-white border rounded-lg shadow-lg overflow-hidden h-full">
                                <div class="bg-green-500 text-white p-4">
                                    <h3 class="text-lg font-semibold">Recently Serviced</h3>
                                    <p class="text-2xl font-bold">{{ $carsRecentlyServiced->count() }}</p>
                                </div>
                                <div class="p-4 max-h-64 overflow-y-auto">
                                    @foreach($carsRecentlyServiced->take(5) as $car)
                                        <div class="mb-3 last:mb-0 bg-gray-50 p-3 rounded shadow">
                                            <h4 class="font-semibold">{{ $car->name ?? $car->license_plate }}</h4>
                                            <p class="text-sm text-gray-600">
                                                Serviced: {{ \Carbon\Carbon::parse($car->last_service_date)->format('d M Y') }}
                                            </p>
                                        </div>
                                    @endforeach
                                    @if($carsRecentlyServiced->count() > 5)
                                        <div class="text-center mt-3 text-gray-600 font-medium">
                                            View {{ $carsRecentlyServiced->count() - 5 }} more cars →
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endif

        @else
            <!-- Role Selection Message -->
            <div class="text-center py-12">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 max-w-md mx-auto">
                    <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-full mx-auto mb-4">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-yellow-800 mb-2">No Access</h3>
                    <p class="text-yellow-700 mb-4">Please select a role to access the dashboard</p>
                    <a href="{{ route('request') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors">
                        Select Role
                    </a>
                </div>
            </div>
        @endif
    @endauth

    @guest()
        <!-- Guest Message -->
        <div class="text-center py-12">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 max-w-md mx-auto">
                <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mx-auto mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-blue-800 mb-2">Login Required</h3>
                <p class="text-blue-700 mb-4">Please login to access the dashboard</p>
                <div class="space-x-2">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-white text-blue-500 border border-blue-500 rounded-md hover:bg-blue-50 transition-colors">
                        Register
                    </a>
                </div>
            </div>
        </div>
    @endguest
</div>
