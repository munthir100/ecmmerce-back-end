<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreFeatureItem extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        "store_feature_id",
        "icon",
        "title",
        "subtitle"
    ];
    function feature()
    {
        return $this->belongsTo(StoreFeature::class);
    }
}
