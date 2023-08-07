<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreCountry extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_active',
        'is_default',
        'store_id',
        'country_id',
    ];
}
