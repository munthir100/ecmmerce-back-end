<?php

namespace Modules\Shipping\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Customer\Entities\Customer;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'city_id',
        'name',
        'lang',
        'lat',
        'phone',
        'address_type',
        'customer_id'
    ];

    function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
