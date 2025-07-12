<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    protected $guarded = [];
    public function reportServiceItems()
    {
        return $this->hasMany(ReportServiceItem::class);
    }

}
