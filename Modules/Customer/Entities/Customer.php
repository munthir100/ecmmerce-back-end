<?php

namespace Modules\Customer\Entities;

use Modules\Acl\Entities\User;
use App\Filters\CustomerFilters;
use Modules\Store\Entities\Store;
use Modules\Shipping\Entities\City;
use Illuminate\Database\Eloquent\Model;
use Essa\APIToolKit\Filters\Filterable;
use Modules\Shipping\Entities\Location;
use Modules\Customer\Entities\ShoppingCart;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, Filterable,SoftDeletes;

    protected $fillable = [
        'birth_date',
        'gender',
        'description',
        'number_of_orders',
        'city_id',
        'user_id',
        'store_id',
    ];

    protected string $default_filters = CustomerFilters::class;

    function city()
    {
        return $this->belongsTo(City::class);
    }
    function user()
    {
        return $this->belongsTo(User::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function shoppingCart()
    {
        return $this->hasOne(ShoppingCart::class);
    }
    public function locations()
    {
        return $this->hasMany(Location::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function couponUsages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    //scopes
    public function scopeForAdmin($query, $adminId)
    {
        return $query->whereHas('store.admin', function ($query) use ($adminId) {
            $query->where('id', $adminId);
        });
    }

    // attributed

    protected function NumberOfOrders(): Attribute
    {
        return Attribute::make()->get(fn () => $this->orders()->count());
    }
}
