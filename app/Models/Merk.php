<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merk extends Model
{
    protected $guarded = [];

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $prefix = 'MI';

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
}
