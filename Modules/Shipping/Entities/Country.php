<?php

namespace Modules\Shipping\Entities;

use App\Filters\CountryFilters;
use Essa\APIToolKit\Filters\Filterable;
use Modules\Store\Entities\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shipping\Database\factories\CountryFactory;

class Country extends Model
{
    use HasFactory,SoftDeletes,Filterable;

    protected string $default_filters = CountryFilters::class;


    protected static function newFactory()
    {
        return CountryFactory::new();
    }
    protected $fillable = ['name','phone_code','phone_digits_number','currency_code'];
    



    function cities()
    {
        return $this->hasMany(City::class);
    }
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_countries');
    }
    // scopes
    public function scopeIsActive(Builder $query)
    {
        return $query->where('is_active', true);
    }
}
