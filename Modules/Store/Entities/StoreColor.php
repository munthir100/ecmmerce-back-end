<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreColor extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'button_color',
        'text_color',
    ];
}
