<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Store\Entities\StoreFeatureItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreFeature extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [];

    function items()
    {
        return $this->hasMany(StoreFeatureItem::class);
    }
}
