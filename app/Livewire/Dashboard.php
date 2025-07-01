<?php

namespace App\Livewire;

use App\Models\Item;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalItems;
    public $expiredItems;
    public $expiringSoonItems;
    public $validItems;

    public function mount()
    {
        $this->updateStats();
    }

    public function updateStats()
    {
        $items = Item::with('latestCalibration')->get();
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


    public function render()
    {
        return view('livewire.dashboard');
    }
}
