<div x-data>
    <div  x-data x-intersect.once="$wire.loadMore()"></div>

    <div class="space-y-4">
        @foreach ($configs as $config)
            <div class="bg-white border rounded-lg p-4 shadow-sm">
                <p class="text-sm text-gray-800 font-semibold">{{ $config->lab_name }}</p>
                <p class="text-xs text-gray-500">Calibrated: {{ \Carbon\Carbon::parse($config->calibrate_at)->format('d M Y') }}</p>
                <p class="text-xs text-gray-500">Expires: {{ \Carbon\Carbon::parse($config->expired_at)->format('d M Y') }}</p>
            </div>
        @endforeach
    </div>

    @if ($configs->hasMorePages())
        <div
            x-intersect="$wire.loadMore()"
            class="text-center text-sm text-gray-500 py-4"
        >
            Loading more...
        </div>
    @endif
</div>
