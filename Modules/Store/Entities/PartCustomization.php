<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartCustomization extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ["store_id", "customizable_id", 'customizable_type'];
}
