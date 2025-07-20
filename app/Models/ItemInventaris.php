<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ItemInventaris extends Model
{
    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = 'string';


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $prefix = 'I';

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
    public function getCurrentExpiredAttribute()
    {
        return $this->latestCalibration?->expired_at ?? $this->expired_at;
    }


    public function getConditionAttribute()
    {
        return $this->status?->condition;
    }
    public function isExpired()
    {
        if (!$this->current_expired()) {
            return false;
        }
        return Carbon::parse($this->current_expired())->lt(now());
    }

    public function isExpiringSoon()
    {
        if (!$this->current_expired()) {
            return false;
        }
        $expiryDate = Carbon::parse($this->current_expired());
        return !$this->isExpired() && $expiryDate->lt(now()->addMonths(3));
    }

    public function isValid()
    {
        if (!$this->current_expired()) {
            return true;
        }
        return Carbon::parse($this->current_expired())->gt(now()->addMonths(3));
    }

    public function getExpirationStatus()
    {
        if ($this->isExpired()) {
            return 'expired';
        } elseif ($this->isExpiringSoon()) {
            return 'expiring_soon';
        } else {
            return 'valid';
        }
    }

    public function getExpirationCardClass()
    {
        if (!$this->current_expired()) {
            return 'border-gray-200';
        }

        return match($this->getExpirationStatus()) {
            'expired' => 'border-red-200 bg-red-50',
            'expiring_soon' => 'border-yellow-200 bg-yellow-50',
            'valid' => 'border-green-200 bg-green-50',
        };
    }

    public function getExpirationTextClass()
    {
        if (!$this->current_expired()) {
            return 'text-gray-900';
        }

        return match($this->getExpirationStatus()) {
            'expired' => 'text-red-600 font-medium',
            'expiring_soon' => 'text-yellow-600 font-medium',
            'valid' => 'text-green-600 font-medium',
        };
    }
    public function latestCalibration()
    {
        return $this->hasOne(Configure::class,'item_id')->latestOfMany('calibrate_at');
    }



    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function configure()
    {
        return $this->hasOne(Configure::class,'item_id');
    }
    public function ruangan()
    {

        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }


    public function status()
    {
        return $this->belongsTo(ItemStatus::class, 'items_status_id');
    }}
