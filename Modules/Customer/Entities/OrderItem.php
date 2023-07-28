<?php

namespace Modules\Customer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Store\Entities\Product;

class OrderItem extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'quantity',
        'order_id',
        'product_id',
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
