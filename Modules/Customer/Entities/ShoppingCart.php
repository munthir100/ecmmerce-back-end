<?php

namespace Modules\Customer\Entities;

use Modules\Store\Entities\Product;
use Illuminate\Database\Eloquent\Model;
use Modules\Customer\Entities\ShoppingCartItem;
use Modules\Customer\Entities\Customer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShoppingCart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'quantity',
        'product_option',
        'product_option_value'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'shopping_cart_items')->withPivot(
            'quantity',
            'product_option',
            'product_option_value'
        );
    }
    public function items()
    {
        return $this->hasMany(ShoppingCartItem::class);
    }

    public function getTotalPriceAttribute()
    {
        $totalPrice = 0;

        foreach ($this->products as $product) {
            $totalPrice += $product->price * $product->pivot->quantity;
        }

        return $totalPrice;
    }
}
