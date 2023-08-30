<?php

namespace Modules\Store\Entities;

use Modules\Store\Entities\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdditionalSetting extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['setting_name','setting_value','store_id'];

    function store()
    {
        return $this->belongsTo(Store::class);
    }
}
