<?php

namespace Modules\Customer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'quantity',
        'payment',
        'order_id',
        'product_id',
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
