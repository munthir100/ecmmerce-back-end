<?php

namespace Modules\Customer\Entities;

use App\Traits\Searchable;
use Modules\Store\Entities\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shipping\Entities\Captain;
use Modules\Shipping\Entities\Location;

class Order extends Model
{
    use HasFactory,SoftDeletes,Searchable;

    protected $searchable = ['items.product.name'];
    
    protected $fillable = [
        'customer_id',
        'store_id',
        'captain_id',
        'location_id',
        'status',
        'total_price',
        'payment_type',
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

    //scopes 

    public function scopeForAdmin($query, $adminId)
    {
        return $query->whereHas('store.admin', function ($query) use ($adminId) {
            $query->where('id', $adminId);
        });
    }
}
