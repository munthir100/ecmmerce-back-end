<?php

namespace Modules\Customer\Entities;

use App\Filters\OrderFilters;
use Modules\Store\Entities\Store;
use Modules\Shipping\Entities\Captain;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Modules\Shipping\Entities\Location;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Entities\Status;

class Order extends Model
{
    use HasFactory,SoftDeletes,Filterable, CascadeSoftDeletes;

    protected $cascadeDeletes = [
        'items',
    ];

    protected string $default_filters = OrderFilters::class;
    
    protected $fillable = [
        'customer_id',
        'store_id',
        'captain_id',
        'location_id',
        'total_price',
        'payment_type',
        'status_id'
    ];

    function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    function captain()
    {
        return $this->belongsTo(Captain::class);
    }
    function store()
    {
        return $this->belongsTo(Store::class);
    }
    function location()
    {
        return $this->belongsTo(Location::class);
    }
    function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    function status()
    {
        return $this->belongsTo(Status::class);
    }
    //scopes 

    public function scopeForAdmin($query, $adminId)
    {
        return $query->whereHas('store.admin', function ($query) use ($adminId) {
            $query->where('id', $adminId);
        });
    }
}
