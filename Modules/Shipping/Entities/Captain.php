<?php

namespace Modules\Shipping\Entities;

use App\Traits\ModelsForStore;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Customer\Entities\Order;
use Modules\Store\Entities\Store;

class Captain extends Model
{
    use HasFactory, SoftDeletes, Searchable,ModelsForStore;

    protected $fillable = [
        'name',
        'shipping_cost',
        'cash_on_delivery',
        'cash_on_delivery_cost',
        'expected_time_shipping',
        'store_id',
        'is_active',
    ];
    protected $searchable = ['name', 'cities.name'];
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
