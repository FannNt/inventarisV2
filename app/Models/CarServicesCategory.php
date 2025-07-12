<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarServicesCategory extends Model
{
    protected $guarded = [];
    public function carService()
    {
        return $this->hasMany(CarService::class);
    }
}
