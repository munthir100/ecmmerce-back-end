<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

   
    const ORDER_NEW = 1;
    const ORDER_PROCESSING =2;
    const ORDER_READY = 3;
    const ORDER_DELIVERING = 4;
    const ORDER_COMPLETED = 5;
    const ORDER_REJECTED = 6;
}
