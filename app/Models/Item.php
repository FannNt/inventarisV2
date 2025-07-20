<?php

namespace App\Models;

use App\Exports\ItemsExport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Excel;

class Item extends Model
{
    use HasFactory;
    protected $guarded = [];

    public $incrementing = false;
    protected $keyType = 'string';


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $prefix = 'BI';

                $lastId = static::where('id', 'like', $prefix . '%')
                    ->orderByDesc('id')
                    ->first()?->id;

                $nextNumber = 1;

                if ($lastId) {
                    $numberPart = (int) substr($lastId, strlen($prefix));
                    $nextNumber = $numberPart + 1;
                }

                $model->id = $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function merk()
    {
        return $this->belongsTo(Merk::class);
    }

    public function configure()
    {
        return $this->hasMany(Configure::class, 'item_id');
    }



    public function itemInventaris()
    {
        return $this->hasMany(ItemInventaris::class);
    }
}
