<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $item->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">Calibration Item Details</p>
            </div>
            <a href="{{ route('items') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Items
            </a>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 py-6">
        <!-- Status Banner -->
        @php
            $today = now();
            $expiry = $item->current_expired ? \Carbon\Carbon::parse($item->current_expired) : null;
            $statusColor = 'bg-gray-50 border-gray-200';
            $statusText = 'No expiry date';
            $statusIcon = '📄';

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
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-xl">{{ $statusIcon }}</span>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $statusText }}</p>
                        <p class="text-sm text-gray-600">
                            {{ $expiry ? 'Expires: ' . $expiry->format('d M Y') : 'No expiration date set' }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs uppercase tracking-wide text-gray-500 font-medium">Condition</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1
                        {{ $item->condition === 'baik' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $item->condition }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Item Information - Clean List Style -->
            <div class="lg:col-span-2 space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        🔧
                    </span>
                    Item Information
                </h3>

                <div class="bg-white rounded-lg border border-gray-200 divide-y divide-gray-100">
                    <!-- Item ID -->
                    <div class="px-6 py-4 flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Item ID</span>
                        <span class="text-sm font-mono text-gray-900 bg-gray-50 px-3 py-1 rounded">{{ $item->id }}</span>
                    </div>

                    <!-- Name -->
                    <div class="px-6 py-4 flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Name</span>
                        <span class="text-sm text-gray-900 font-medium">{{ $item->item->name }}</span>
                    </div>

                    <!-- Brand -->
                    <div class="px-6 py-4 flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Brand</span>
                        <span class="text-sm text-gray-900">{{ $item->item->merk->name }}</span>
                    </div>

                    <!-- Serial Number -->
                    <div class="px-6 py-4 flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Serial Number</span>
                        <span class="text-sm font-mono text-gray-900 bg-gray-50 px-3 py-1 rounded">{{ $item->item->no_seri }}</span>
                    </div>

                    <!-- Year of Procurement -->
                    <div class="px-6 py-4 flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Year of Procurement</span>
                        <span class="text-sm text-gray-900">{{ $item->tgl_pengadaan }}</span>
                    </div>

                    <!-- Validity Period -->
                    <div class="px-6 py-4 flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Validity Period</span>
                        <span class="text-sm text-gray-900">{{ $item->current_expired ?: 'Not specified' }}</span>
                    </div>
                </div>
            </div>

            <!-- Location & Lab Card -->
            <div class="space-y-6">
                <!-- Location Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                📍
                            </span>
                            Location
                        </h3>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-700">Room</span>
                                <span class="text-sm text-gray-900 font-medium">{{ $item->ruangan->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Laboratory Configuration Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                🧪
                            </span>
                            Configuration History
                        </h3>

                        <div class="space-y-3">
                            <livewire:configure-history :item-id="$item->id" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @auth()
            @if(auth()->user()->hasAnyRole(['admin','superadmin','items_management']))
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span class="text-sm">📊</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <a href="{{url("/admin/items/$item->id/edit")}}" class="text-sm font-medium text-gray-600">Edit Data</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endauth
    </div>
</x-app-layout>
