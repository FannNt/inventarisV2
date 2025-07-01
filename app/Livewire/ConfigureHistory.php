<?php

namespace App\Livewire;

use App\Models\Configure;
use Livewire\Component;
use Livewire\WithPagination;

class ConfigureHistory extends Component
{
    use WithPagination;

    public $itemId;
    public $perPage = 2;

    protected $listeners = ['loadMore'];

    public function loadMore()
    {
        logger('success');
        $this->perPage += 2;
    }

    public function render()
    {
        $configs = Configure::where('item_id', $this->itemId)
            ->latest('calibrated_at')
            ->paginate($this->perPage);

        return view('livewire.configure-history', [
            'configs' => $configs
        ]);
    }

}
