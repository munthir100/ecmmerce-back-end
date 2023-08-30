<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreColor extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'store_id',
        'button_color',
        'text_color',
    ];
}
