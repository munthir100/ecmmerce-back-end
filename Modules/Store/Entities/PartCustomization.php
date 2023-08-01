<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartCustomization extends Model
{
    use HasFactory;

    protected $fillable = ["store_id", "customizable_id", 'customizable_type'];
}
