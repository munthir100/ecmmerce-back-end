<?php

namespace Modules\Shipping\Entities;

use App\Traits\Searchable;
use App\Filters\CityFilters;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory,Searchable,SoftDeletes,Filterable;
    protected string $default_filters = CityFilters::class;

    protected $searchable = ['name'];
    protected $fillable = ['name','country_id'];


    public function captains()
    {
        return $this->belongsToMany(Captain::class);
    }
}
