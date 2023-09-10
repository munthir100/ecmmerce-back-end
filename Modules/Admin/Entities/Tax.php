<?php

namespace Modules\Admin\Entities;

use App\Filters\TaxFilters;
use Modules\Store\Entities\Store;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tax extends Model
{
    use HasFactory, SoftDeletes,Filterable;

    protected $fillable = [
        'name',
        'number',
        'precentage',
        'merchant_borne_tax',
        'is_active',
        'store_id',
    ];
    protected string $default_filters = TaxFilters::class;

    function store()
    {
        return $this->belongsTo(Store::class);
    }
}
