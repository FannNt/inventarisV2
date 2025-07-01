<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configure extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function item()
    {
        return $this->belongsToMany(Item::class);
    }
}
