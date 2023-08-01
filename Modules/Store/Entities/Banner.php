<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Store\Entities\PartCustomization;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    public function customizations()
    {
        return $this->morphMany(PartCustomization::class, 'customizable');
    }
}
