<?php

namespace Modules\Customer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Store\Entities\Product;

class ShoppingCartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'store_id',
        'product_id',
        'shopping_cart_id',
        'product_option',
        'product_option_value',
        'additional_price'
    ];

    function product()
    {
        return $this->belongsTo(Product::class);
    }
}
