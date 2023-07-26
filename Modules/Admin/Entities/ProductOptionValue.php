<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductOptionValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'additional_price',
        'quantity',
        'product_option_id',
    ];

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
