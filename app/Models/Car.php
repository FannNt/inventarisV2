<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function service()
    {
        return $this->hasMany(CarService::class);
    }
    public function latestService()
    {
        return $this->hasOne(CarService::class)->latestOfMany('service_at');
    }

    public function getCurrentServiceAttribute()
    {
        return $this->latestService?->service_at;
    }

    public function gatNeedServiceAttribute()
    {
        $latestService= $this?->current_service;
        $expired =  Carbon::parse($latestService)->addMonths(6);
        return $expired;
    }

}
