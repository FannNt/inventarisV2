<?php

namespace App\Livewire;

use App\Models\Configure;
use App\Models\Item;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class Items extends Component
{
    use WithPagination;
    public $name;
    public $id;
    public $ruangan_id;
    public $merk;
    public $status_id;
    public $tahun_pengadaan;
    public $masa_berlaku;
    public $isOpen = false;

    public $search = '';
    public $kondisi_filter = '';
    public $ruangan_filter = '';
    public $expirationFilter = '';
    public $no_seri = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'ruangan_filter' => ['except' => ''],
        'kondisi_filter' => ['except' => ''],
        'expirationFilter' => ['except' => '']
    ];


    protected $rules = [
        'name' => 'required',
        'merk' => 'required',
        'tahun_pengadaan' => 'required|numeric|min:1900|max:2100',
        'masa_berlaku' => 'required|date',
        'ruangan_id' => 'required|exists:ruangans,id',
    ];
    protected $messages = [
        'keterangan.required_if' => 'Keterangan wajib diisi jika kondisi rusak.',
        'tahun_pengadaan.min' => 'Tahun tidak valid',
        'tahun_pengadaan.max' => 'Tahun tidak valid'
    ];

    public function mount()
    {
        $this->expirationFilter = request()->query('filter', '');
    }

    public function updatedExpirationFilter($value)
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'ruangan_filter', 'kondisi_filter', 'expirationFilter']);
    }

    public function render()
    {
        $query = Item::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->ruangan_filter, function($query) {
                $query->where('ruangan_id', 'like', $this->ruangan_filter );
            })
            ->when($this->kondisi_filter, function($query) {
                $query->whereHas('status', function ($q){
                    $q->where('condition', $this->kondisi_filter);
                });
            });


        $now = Carbon::now();
        $soon = now()->addMonths(3);

        switch ($this->expirationFilter) {
            case 'expired':
                $query->where(function ($q) use ($now) {
                    $q->whereHas('latestCalibration', function ($sub) use ($now) {
                        $sub->whereNotNull('expired_at')
                            ->where('expired_at', '<', $now);
                    })->orWhere(function ($sub) use ($now) {
                        $sub->whereNull('expired_at')->whereDoesntHave('latestCalibration');
                    })->orWhere(function ($sub) use ($now) {
                        $sub->where('expired_at', '<', $now)->whereDoesntHave('latestCalibration');
                    });
                });
                break;

            case 'expiring_soon':
                $query->where(function ($q) use ($now, $soon) {
                    $q->whereHas('latestCalibration', function ($sub) use ($now, $soon) {
                        $sub->whereBetween('expired_at', [$now, $soon]);
                    })->orWhere(function ($sub) use ($now, $soon) {
                        $sub->whereBetween('expired_at', [$now, $soon])->whereDoesntHave('latestCalibration');
                    });
                });
                break;

            case 'valid':
                $query->where(function ($q) use ($soon) {
                    $q->whereHas('latestCalibration', function ($sub) use ($soon) {
                        $sub->where('expired_at', '>', $soon);
                    })->orWhere(function ($sub) use ($soon) {
                        $sub->where(function ($inner) use ($soon) {
                            $inner->whereNull('expired_at')
                                ->orWhere('expired_at', '>', $soon);
                        })->whereDoesntHave('latestCalibration');
                    });
                });
                break;
        }

        $items = $query->paginate(12);

        return view('livewire.items.items', [
            'items' => $items,
            'ruangans' => Ruangan::all(),
            'years' => collect(range(date('Y'), date('Y')-30))->map(function($year) {
                return ['label' => $year, 'value' => $year];
            })
        ]);
    }
}
