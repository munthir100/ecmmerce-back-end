<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name'];
    
    const ARABIC = 1;
    const ENGLISH = 2;
}
