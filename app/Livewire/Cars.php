<?php

namespace App\Livewire;

use App\Models\Car;
use App\Models\CarServicesCategory;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Cars extends Component
{
    use WithPagination;
    public $name;
    public $atas_nama;
    public $type;
    public $nopol;
    public $tahunPerakitan;
    public $tahunPembelian;
    public $bahanBakar;
    public $warna;
    public $tanggalPajak;
    public $fungsi;
    public $odometer;

    public $expirationFilter = '';
    public $serviceFilter = '';
    public $fungsiFilter = '';
    public $search = '';

    protected $queryString = [
        'search' => ['expect' => ''],
        'expirationFilter' => ['expect' => ''],
        'serviceFilter' => ['expect' => ''],
        'fungsiFilter' => ['expect' => ''],
    ];


    public function updatedExpirationFilter()
    {
        $this->resetPage();
    }
    public function clearFilters()
    {
        $this->reset(['search', 'expirationFilter','serviceFilter','fungsiFilter']);
    }

    public function mount()
    {
        $this->expirationFilter = request()->query('filter', '');
    }

    public function render()
    {
        $query = Car::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('nopol', 'like' , '%' . $this->search . '%');
            })
            ->when($this->fungsiFilter, function($query) {
                $query->where('fungsi', 'like', $this->fungsiFilter );
            })
            ->when($this->serviceFilter, function($query) {
                $query->whereHas('latestService', function ($q){
                    $q->where('service_at', 'like' . $this->serviceFilter);
                });
            });

        $now = Carbon::now();
        $sixMonthsAgo = $now->copy()->subMonths(6);

        switch ($this->expirationFilter) {
            case 'need_service':
                $query->whereHas('latestService', function ($sub) use ($sixMonthsAgo) {
                    $sub->whereNotNull('service_at')
                        ->where('service_at', '<', $sixMonthsAgo);
                });
                break;

            case 'warning_service':
                $threeMonthsAgo = $now->copy()->subMonths(3);
                $query->whereHas('latestService', function ($sub) use ($sixMonthsAgo, $threeMonthsAgo) {
                    $sub->whereNotNull('service_at')
                        ->where('service_at', '>=', $sixMonthsAgo)
                        ->where('service_at', '<', $threeMonthsAgo);
                });
                break;

            case 'serviced':
                $threeMonthsAgo = $now->copy()->subMonths(3);
                $query->whereHas('latestService', function ($sub) use ($threeMonthsAgo) {
                    $sub->whereNotNull('service_at')
                        ->where('service_at', '>=', $threeMonthsAgo);
                });
                break;
        }
        $cars = $query->paginate(12);
        return view('livewire.cars.cars' , [
            'cars' => $cars,
            'years' => collect(range(date('Y'), date('Y')-30))->map(function($year) {
                return ['label' => $year, 'value' => $year];
            }),
        ]);
    }
}
