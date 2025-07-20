<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class   Configure extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $prefix = 'CI';

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

    public function itemInventaris()
    {
        return $this->belongsToMany(ItemInventaris::class,'item_id');
    }
}
