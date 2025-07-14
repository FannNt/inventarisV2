<div>
    <div class="max-w-6xl mx-auto px-4 py-6">
        <!-- Status Banner -->
        @php
            $today = now();
            $latestService = $car->current_service;
            $expiry = $latestService ? \Carbon\Carbon::parse($latestService)->addMonths(6) : null;
            $statusColor = 'bg-gray-50 border-gray-200';
            $statusText = 'No expiry date';
            $statusIcon = '📄';

            $canViewSensitiveData = auth()->check() && auth()->user()->hasAnyRole(['admin','superadmin','cars_management']);

            if ($expiry) {
                if ($expiry->lt($today)) {
                    $statusColor = 'bg-red-50 border-red-200';
                    $statusText = 'Expired';
                    $statusIcon = '🚨';
                } elseif ($expiry->lt($today->copy()->addMonths(3))) {
                    $statusColor = 'bg-yellow-50 border-yellow-200';
                    $statusText = 'Expiring Soon';
                    $statusIcon = '⚠️';
                } else {
                    $statusColor = 'bg-green-50 border-green-200';
                    $statusText = 'Valid';
                    $statusIcon = '✅';
                }
            }
        @endphp

        <div class="mb-6 p-4 rounded-xl border {{ $statusColor }}">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <span class="text-xl">{{ $statusIcon }}</span>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $statusText }}</p>
                        <p class="text-sm text-gray-600">
                            {{ $expiry ? 'Next service due: ' . $expiry->format('d M Y') : 'No service schedule set' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Vehicle Image -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="aspect-w-16 aspect-h-12 bg-gray-100">
                        @if($car->image)
                            <img src="{{ asset('storage/' . $car->image) }}"
                                 alt="{{ $car->name }}"
                                 class="w-full h-64 object-cover">
                        @else
                            <div class="w-full h-64 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"/>
                                    </svg>
                                    <p class="text-sm text-gray-500">No image available</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center mb-2">
                            <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                🚗
                            </span>
                            {{ $car->name }}
                        </h3>
                        <p class="text-sm text-gray-600">{{ $car->type }}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">License Plate</span>
                            <span class="text-sm font-mono bg-blue-50 text-blue-800 px-2 py-1 rounded">{{ $car->nopol }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                @if($canViewSensitiveData)
                    <div class="mt-4 bg-white rounded-lg border border-gray-200 p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Quick Actions</h4>
                        <div class="space-y-2">
                            <a class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors" href="/admin/cars/{{$car->id}}/edit">
                                📝 Add Service Record
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Vehicle Information -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                🔧
                            </span>
                            Vehicle Information
                        </h3>
                    </div>

                    <div class="divide-y divide-gray-100">
                        <div class="px-4 sm:px-6 py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                            <span class="text-sm font-medium text-gray-600">License Plate</span>
                            <span class="text-sm font-mono text-gray-900 bg-gray-50 px-3 py-1 rounded">{{ $car->nopol }}</span>
                        </div>

                        <div class="px-4 sm:px-6 py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                            <span class="text-sm font-medium text-gray-600">Vehicle Name</span>
                            <span class="text-sm text-gray-900 font-medium">{{ $car->name }}</span>
                        </div>

                        <div class="px-4 sm:px-6 py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                            <span class="text-sm font-medium text-gray-600">Type</span>
                            <span class="text-sm text-gray-900">{{ $car->type }}</span>
                        </div>

                        @if($canViewSensitiveData)
                            <div class="px-4 sm:px-6 py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                                <span class="text-sm font-medium text-gray-600">Engine Number</span>
                                <span class="text-sm font-mono text-gray-900 bg-gray-50 px-3 py-1 rounded">{{ $car->nomor_mesin }}</span>
                            </div>

                            <div class="px-4 sm:px-6 py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                                <span class="text-sm font-medium text-gray-600">Tax Date</span>
                                <span class="text-sm text-gray-900">{{ $car->tanggal_pajak }}</span>
                            </div>
                        @endif

                        <div class="px-4 sm:px-6 py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                            <span class="text-sm font-medium text-gray-600">Last Service</span>
                            <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($latestService)->format('d M Y') ?: 'Not specified' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Service History -->
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                📋
                            </span>
                            Service History
                        </h3>
                        <button class="text-sm text-blue-600 hover:text-blue-800 font-medium self-start sm:self-center">
                            View All
                        </button>
                    </div>

                    <div class="p-4 sm:p-6">
                        @if($car->service && $car->service->count() > 0)
                            <div class="space-y-4">
                                @foreach($car->service->take(5) as $service)
                                    <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors"
                                         wire:click="openServiceModal({{ $service->id }})">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600">
                                                    {{ \Carbon\Carbon::parse($service->service_at)->format('d') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $service->name}}
                                                </p>
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-xs text-gray-500">
                                                        {{ \Carbon\Carbon::parse($service->service_at)->format('d M Y') }}
                                                    </span>
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1">
                                                {{ $service->kategori->name ?? 'Regular Service' }}
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                {{ $service->keterangan ?? 'Service completed' }}
                                            </p>
                                            @if($car->odometer && $canViewSensitiveData)
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Mileage: {{ number_format($car->odometer) }} km
                                                </p>
                                            @endif
                                            @if($service->total && $canViewSensitiveData)
                                                <p class="text-xs text-green-600 mt-1 font-medium">
                                                    Total: Rp {{ number_format($service->total) }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">No Service History</h4>
                                <p class="text-sm text-gray-500 mb-4">This vehicle doesn't have any service records yet.</p>
                                @if($canViewSensitiveData)
                                    <a class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors " href="/admin/cars/{{$car->id}}/edit">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Add First Service Record
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="text-sm">📊</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Total Services</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $car->service ? $car->service->count() : 0 }}</p>
                    </div>
                </div>
            </div>

            @if($canViewSensitiveData)
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <span class="text-sm">💰</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">Total Cost</p>
                            <p class="text-lg font-semibold text-gray-900">
                                Rp {{ number_format($car->service ? $car->service->sum('total') : 0) }}
                            </p>
                        </div>
                    </div>
                </div>

            @else
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-sm">🔒</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">Financial Data</p>
                            <p class="text-sm text-gray-500">Restricted Access</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Enhanced Responsive Modal -->
        @if($isModalOpen)
            <!-- Modal Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity" wire:click="closeModal"></div>

            <!-- Modal Container -->
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen p-4 text-center">
                    <!-- Modal Content -->
                    <div class="relative inline-block w-full max-w-6xl bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all">
                        <!-- Modal Header -->
                        <div class="bg-white px-4 sm:px-6 py-4 border-b border-gray-200">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-lg">🔧</span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Service Detail
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            {{ $service ? \Carbon\Carbon::parse($service->service_at)->format('d M Y') : '' }}
                                        </p>
                                    </div>
                                </div>
                                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors self-end sm:self-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        @if($service)
                            <!-- Modal Body -->
                            <div class="bg-white px-4 sm:px-6 py-4 max-h-96 sm:max-h-[32rem] overflow-y-auto">
                                <!-- Service Information -->
                                <div class="mb-6">
                                    <h4 class="text-md font-semibold text-gray-900 mb-3">Service Information</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Service Type</label>
                                            <p class="text-sm font-medium text-gray-900 mt-1">
                                                {{ $service->kategori->name }}
                                            </p>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Service Date</label>
                                            <p class="text-sm font-medium text-gray-900 mt-1">
                                                {{ \Carbon\Carbon::parse($service->service_at)->format('d M Y') }}
                                            </p>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Vehicle</label>
                                            <p class="text-sm font-medium text-gray-900 mt-1">
                                                {{ $service->car->name ?? 'Unknown' }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $service->car->nopol ?? '' }}
                                            </p>
                                        </div>
                                        @if($canViewSensitiveData)
                                            <div class="bg-gray-50 rounded-lg p-3">
                                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Cost</label>
                                                <p class="text-sm font-medium text-green-600 mt-1">
                                                    Rp {{ number_format($service->total ?? 0) }}
                                                </p>
                                            </div>
                                        @else
                                            <div class="bg-gray-50 rounded-lg p-3">
                                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Cost</label>
                                                <p class="text-sm font-medium text-gray-600 mt-1">
                                                    🔒 Restricted Access
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    @if($service->keterangan)
                                        <div class="mt-4 bg-gray-50 rounded-lg p-3">
                                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Description</label>
                                            <p class="text-sm text-gray-900 mt-1">{{ $service->keterangan }}</p>
                                        </div>
                                    @endif
                                </div>

                                @if($service->image)
                                    <div class="mb-6">
                                        <h4 class="text-md font-semibold text-gray-900 mb-3">Payment Proof</h4>
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <img src="{{ asset('storage/' . $service->image) }}"
                                                 alt="Payment Proof"
                                                 class="w-full max-w-sm rounded-lg border border-gray-200 shadow-sm">
                                        </div>
                                    </div>
                                @endif

                                <!-- Service Items -->
                                <div class="mb-4">
                                    <h4 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
                                        <span class="w-6 h-6 bg-green-100 rounded-lg flex items-center justify-center mr-2">
                                            <span class="text-xs">🔧</span>
                                        </span>
                                        Repaired Items
                                    </h4>

                                    @if($serviceItems && count($serviceItems) > 0)
                                        <div class="space-y-3">
                                            @foreach($serviceItems as $serviceItem)
                                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                                    <div class="flex items-start justify-between">
                                                        <div class="flex-1">
                                                            <div class="flex items-center mb-2">
                                                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                                    <span class="text-sm">🔩</span>
                                                                </div>
                                                                <div>
                                                                    <h5 class="text-sm font-medium text-gray-900">
                                                                        {{ $serviceItem->serviceItem->name ?? 'Unknown Item' }}
                                                                    </h5>
                                                                </div>
                                                            </div>

                                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-sm">
                                                                <div>
                                                                    <span class="text-xs font-medium text-gray-500">Quantity</span>
                                                                    <p class="text-gray-900">{{ $serviceItem->quantity ?? 1 }}</p>
                                                                </div>
                                                                @if($canViewSensitiveData)
                                                                    <div>
                                                                        <span class="text-xs font-medium text-gray-500">Unit Price</span>
                                                                        <p class="text-gray-900">Rp {{ number_format($serviceItem->price ?? 0) }}</p>
                                                                    </div>
                                                                    <div>
                                                                        <span class="text-xs font-medium text-gray-500">Total</span>
                                                                        <p class="text-green-600 font-medium">
                                                                            Rp {{ number_format(($serviceItem->quantity ?? 1) * ($serviceItem->price ?? 0)) }}
                                                                        </p>
                                                                    </div>
                                                                @else
                                                                    <div>
                                                                        <span class="text-xs font-medium text-gray-500">Unit Price</span>
                                                                        <p class="text-gray-600">🔒 Restricted</p>
                                                                    </div>
                                                                    <div>
                                                                        <span class="text-xs font-medium text-gray-500">Total</span>
                                                                        <p class="text-gray-600">🔒 Restricted</p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Items Summary -->
                                        <div class="mt-4 bg-gray-50 rounded-lg p-4">
                                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                                                <div class="flex items-center">
                                                    <span class="text-sm font-medium text-gray-600">Total Items:</span>
                                                    <span class="ml-2 text-sm font-semibold text-gray-900">{{ count($serviceItems) }}</span>
                                                </div>
                                                @if($canViewSensitiveData)
                                                    <div class="flex items-center">
                                                        <span class="text-sm font-medium text-gray-600">Items Cost:</span>
                                                        <span class="ml-2 text-sm font-semibold text-green-600">
                                                            Rp {{ number_format(collect($serviceItems)->sum(function($item) {
                                                                return ($item->quantity ?? 1) * ($item->price ?? 0);
                                                            })) }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="flex items-center">
                                                        <span class="text-sm font-medium text-gray-600">Items Cost:</span>
                                                        <span class="ml-2 text-sm text-gray-600">🔒 Restricted Access</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-8">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <h4 class="text-lg font-medium text-gray-900 mb-2">No Items Found</h4>
                                            <p class="text-sm text-gray-500">This service record doesn't have any repair items listed.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="bg-gray-50 px-4 sm:px-6 py-4 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                                <button wire:click="closeModal"
                                        class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                    Close
                                </button>
                                @if($canViewSensitiveData)
                                    <a class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors" href="/admin/cars/{{$car->id}}/edit">
                                        Edit Service
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
