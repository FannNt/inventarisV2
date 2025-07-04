<?php

namespace App\Livewire;

use App\Models\RoleRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ApplyRole extends Component
{
    public $hasPending;

    public function mount()
    {
        $this->checkPending();
    }

    public function apply($role)
    {
        if ($this->hasPending) return;

        RoleRequest::create([
            'user_id' => Auth::id(),
            'role_request' => $role,
        ]);
        $this->checkPending();
        session()->flash('success', 'Request has been submitted, please wait admin to accept');
    }
    public function checkPending()
    {
        $this->hasPending = RoleRequest::where('user_id',Auth::id())
            ->where('status', 'pending')
            ->count() > 0;
    }
    public function render()
    {
        return view('livewire.apply-role');
    }
}
