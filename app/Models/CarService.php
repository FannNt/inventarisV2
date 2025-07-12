<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarService extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function reportServiceItems()
    {
        return $this->hasMany(ReportServiceItem::class);
    }

    public function kategori()
    {
        return $this->belongsTo(CarServicesCategory::class);
    }

}
