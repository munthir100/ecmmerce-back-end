<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DynamicProduct extends Model
{
    use HasFactory;

    protected $fillable = ['product_id'];
    
}
