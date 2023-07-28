<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Entities\Admin;
use Modules\Customer\Entities\Customer;
use Modules\Shipping\Entities\Captain;
use Modules\Shipping\Entities\City;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['admin_id','name','link'];

    function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
    function categories()
    {
        return $this->hasMany(Category::class);
    }
    function products()
    {
        return $this->hasMany(Product::class);
    }
    function captains()
    {
        return $this->hasMany(Captain::class);
    }
    function city()
    {
        return $this->belongsTo(City::class);
    }
}