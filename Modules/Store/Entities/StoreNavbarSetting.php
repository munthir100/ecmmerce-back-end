<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreNavbarSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        "paragraph",
        "color",
        "link",
        "is_active",
    ];

}
