<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
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
                $prefix = 'RI';

                $lastId = static::where('id', 'like', $prefix . '%')
                    ->orderByDesc('id')
                    ->first()?->id;

                $nextNumber = 1;

                if ($lastId) {
                    $numberPart = (int) substr($lastId, strlen($prefix));
                    $nextNumber = $numberPart + 1;
                }

                $model->id = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
        });
    }
    public function itemInventaris()
    {
        return $this->hasMany(ItemInventaris::class);
    }

}
