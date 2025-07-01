<div class="container mx-auto px-4 py-8">
    <!-- Overview Statistics -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-6">Inventory Overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Total Items</h3>
                <p class="text-3xl font-bold">{{ $totalItems }}</p>
            </div>
        </div>
    </div>

    <!-- Expiration Status Cards -->
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
                                Masa Berlaku: {{ \Carbon\Carbon::parse($item->current_expired)->format('d M Y') }}
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
                                Masa Berlaku: {{ \Carbon\Carbon::parse($item->current_expired)->format('d M Y') }}
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
                                    Masa Berlaku: {{ \Carbon\Carbon::parse($item->current_expired)->format('d M Y') }}
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
