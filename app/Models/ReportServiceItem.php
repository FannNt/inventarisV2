<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportServiceItem extends Model
{
    protected $guarded = [];

    public function carService()
    {
        return $this->belongsTo(CarService::class);
    }

    public function serviceItem()
    {
        return $this->belongsTo(ServiceItem::class);
    }
}
