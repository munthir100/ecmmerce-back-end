<?php

namespace Modules\Customer\Entities;

use Modules\Store\Entities\Product;
use Illuminate\Database\Eloquent\Model;
use Modules\Customer\Entities\Customer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShoppingCart extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'customer_id'
    ];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }
}
