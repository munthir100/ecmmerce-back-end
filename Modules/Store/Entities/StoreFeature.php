<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Store\Entities\StoreFeatureItem;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreFeature extends Model
{
    use HasFactory,SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = [
        'items',
    ];

    protected $fillable = [];

    function items()
    {
        return $this->hasMany(StoreFeatureItem::class);
    }
}
