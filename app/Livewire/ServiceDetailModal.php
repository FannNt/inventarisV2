<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Models\Car;
use App\Models\CarService;

class ServiceDetailModal extends Component
{
    public $carId;
    public $car;
    public $isModalOpen = false;
    public $selectedService;
    public $serviceItems = [];

    public function mount($carId)
    {
        $this->carId = $carId;
        $this->car = Car::with(['service.kategori', 'service.reportServiceItems.serviceItem'])->findOrFail($carId);
        Log::info('Car loaded: ' . $this->car->name);
    }

    public function openServiceModal($serviceId)
    {
        $this->selectedService = CarService::with(['kategori', 'car', 'reportServiceItems.serviceItem'])->find($serviceId);
        $this->serviceItems = $this->selectedService->reportServiceItems ?? [];
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->selectedService = null;
        $this->serviceItems = [];
    }

    public function render()
    {
        return view('livewire.cars.detail');
    }
}
