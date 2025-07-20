<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Car; // Assuming you have a Car model
use App\Models\ItemInventaris;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    // Items stats
    public $totalItems;
    public $expiredItems;
    public $expiringSoonItems;
    public $validItems;

    // Cars stats
    public $totalCars;
    public $carsNeedingService;
    public $carsRecentlyServiced;
    public $carsOverdue;

    // User permissions
    public $canViewItems;
    public $canViewCars;
    public $isAdmin;

    public function mount()
    {
        $this->setPermissions();
        $this->updateStats();
    }

    public function setPermissions()
    {
        $user = auth()->user();
        if ($user) {
            $this->isAdmin = $user->hasAnyRole(['admin', 'superadmin']);
            $this->canViewItems = $user->hasAnyRole(['admin', 'superadmin', 'items_management']);
            $this->canViewCars = $user->hasAnyRole(['admin', 'superadmin', 'cars_management']);
        }
    }

    public function updateStats()
    {
        if ($this->canViewItems) {
            $this->updateItemsStats();
        }

        if ($this->canViewCars) {
            $this->updateCarsStats();
        }
    }

    public function updateItemsStats()
    {
        $items = ItemInventaris::with('latestCalibration')->get();
        $this->totalItems = $items->count();

        $now = now();
        $threeMonths = $now->copy()->addMonths(3);

        $this->expiredItems = $items->filter(function ($item) use ($now) {
            return $item->current_expired && $item->current_expired < $now;
        })->sortBy('current_expired')->values();

        $this->expiringSoonItems = $items->filter(function ($item) use ($now, $threeMonths) {
            return $item->current_expired && $item->current_expired >= $now && $item->current_expired <= $threeMonths;
        })->sortBy('current_expired')->values();

        $this->validItems = $items->filter(function ($item) use ($threeMonths) {
            return !$item->current_expired || $item->current_expired > $threeMonths;
        })->sortBy('current_expired')->values();
    }

    public function updateCarsStats()
    {
        $cars = Car::all();
        $this->totalCars = $cars->count();

        $now = now();
        $sixMonthsAgo = $now->copy()->subMonths(6);
        $threeMonthsAgo = $now->copy()->subMonths(3);

        $this->carsNeedingService = $cars->filter(function ($car) use ($sixMonthsAgo) {
            return $car->latestService && $car->latestService->service_at < $sixMonthsAgo;
        })->sortByDesc(fn ($car) => $car->latestService->service_at ?? null)->values();

        $this->carsOverdue = $cars->filter(function ($car) use ($sixMonthsAgo, $threeMonthsAgo) {
            return $car->latestService
                && $car->latestService->service_at >= $sixMonthsAgo
                && $car->latestService->service_at < $threeMonthsAgo;
        })->sortByDesc(fn ($car) => $car->latestService->service_at ?? null)->values();

        $this->carsRecentlyServiced = $cars->filter(function ($car) use ($threeMonthsAgo) {
            return $car->latestService
                && $car->latestService->service_at >= $threeMonthsAgo;
        })->sortByDesc(fn ($car) => $car->latestService->service_at ?? null)->values();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
