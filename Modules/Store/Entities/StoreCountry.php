<?php

namespace Modules\Store\Entities;

use Modules\Shipping\Entities\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreCountry extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'is_active',
        'is_default',
        'store_id',
        'country_id',
    ];

    public function countries()
    {
        return $this->belongsToMany(Country::class);
    }
}
