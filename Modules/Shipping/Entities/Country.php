<?php

namespace Modules\Shipping\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shipping\Database\factories\CountryFactory;

class Country extends Model
{
    use HasFactory;
    protected static function newFactory()
    {
        return CountryFactory::new();
    }
    protected $fillable = ['name'];

    function cities()
    {
        return $this->hasMany(City::class);
    }
}
