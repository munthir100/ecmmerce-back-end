<?php

namespace Modules\Admin\Entities;

use Modules\Store\Entities\Product;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\ProductOptionValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductOption extends Model
{
    use HasFactory;

    protected $fillable = ['name','product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function values()
    {
        return $this->hasMany(ProductOptionValue::class);
    }
}
