<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DynamicProduct extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['product_id'];
    
}
