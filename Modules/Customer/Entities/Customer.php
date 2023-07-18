<?php

namespace Modules\Customer\Entities;

use App\Traits\Searchable;
use Modules\Acl\Entities\User;
use Modules\Store\Entities\Store;
use Modules\Shipping\Entities\City;
use Illuminate\Database\Eloquent\Model;
use Modules\Customer\Entities\ShoppingCart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shipping\Entities\Location;

class Customer extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'birth_date',
        'gender',
        'description',
        'number_of_orders',
        'city_id',
        'user_id',
        'store_id',
    ];

    protected $searchable = ['user.name', 'user.email', 'user.phone', 'description'];

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

    //scopes
    public function scopeForAdmin($query, $adminId)
    {
        return $query->whereHas('store.admin', function ($query) use ($adminId) {
            $query->where('id', $adminId);
        });
    }
}
