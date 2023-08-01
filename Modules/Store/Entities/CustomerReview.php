<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerReview extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id'];
    
    
}
