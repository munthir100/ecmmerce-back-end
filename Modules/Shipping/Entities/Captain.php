<?php

namespace Modules\Shipping\Entities;

use App\Traits\ModelsForStore;
use App\Filters\CaptainFilters;
use Modules\Store\Entities\Store;
use Modules\Customer\Entities\Order;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Captain extends Model
{
    use HasFactory, SoftDeletes,ModelsForStore,Filterable;

    protected $fillable = [
        'name',
        'shipping_cost',
        'cash_on_delivery',
        'cash_on_delivery_cost',
        'expected_time_shipping',
        'store_id',
        'is_active',
    ];
    protected string $default_filters = CaptainFilters::class;

    function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function cities()
    {
        return $this->belongsToMany(City::class);
    }
    function orders()
    {
        return $this->hasMany(Order::class);
    }
    //scopes
    public function scopeForAdmin($query, $adminId)
    {
        return $query->whereHas('store.admin', function ($query) use ($adminId) {
            $query->where('id', $adminId);
        });
    }
}
