<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Apply for Management Roles</h2>


@if (session()->has('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @else
        @if($hasPending)
            <div class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4">
                <h1>Request role has been submitted, wait until admin accept request</h1>
            </div>
        @endif
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <!-- Car Management -->
        <div class="bg-white rounded-xl border shadow-sm p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Car Management</h3>
                <p class="text-sm text-gray-600 mt-2">
                    Manage all vehicle-related data and maintenance schedules.
                </p>
            </div>
            <button wire:click="apply('cars_management')"
                    class="mt-4 w-full py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700 transition disabled:opacity-50"
                @disabled($hasPending)>
                Apply
            </button>
        </div>

        <!-- Item Management -->
        <div class="bg-white rounded-xl border shadow-sm p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Item Management</h3>
                <p class="text-sm text-gray-600 mt-2">
                    Manage inventory and  calibrations.
                </p>
            </div>
            <button wire:click="apply('items_management')"
                    class="mt-4 w-full py-2 px-4 bg-green-600 text-white rounded hover:bg-green-700 transition disabled:opacity-50"
                @disabled($hasPending)>
                Apply
            </button>
        </div>

        <div class="bg-white rounded-xl border shadow-sm p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Admin</h3>
                <p class="text-sm text-gray-600 mt-2">
                    Manage Inventaris and cars
                </p>
            </div>
            <button wire:click="apply('admin')"
                    class="mt-4 w-full py-2 px-4 bg-green-600 text-white rounded hover:bg-green-700 transition disabled:opacity-50"
                @disabled($hasPending)>
                Apply
            </button>
        </div>
    </div>
</div>
