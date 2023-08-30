<?php

namespace Modules\Customer\Entities;

use Modules\Store\Entities\Product;
use Illuminate\Database\Eloquent\Model;
use Modules\Customer\Entities\Customer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Customer\Entities\ShoppingCartItem;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShoppingCart extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = [
        'items',
    ];

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
            'product_option_value',
            'additional_price',
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

    public function getFeaturedProductTotalPriceAttribute()
    {
        $totalPrice = 0;

        foreach ($this->products as $product) {
            $totalPrice += $product->price + ($product->pivot->quantity * $product->pivot->additional_price);
        }

        return $totalPrice;
    }
}
