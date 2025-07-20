<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function itemInventaris()
    {
        return $this->hasOne(ItemInventaris::class, 'items_status_id');
    }
}
