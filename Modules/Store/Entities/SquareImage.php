<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SquareImage extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['title'];

    public function items()
    {
        return $this->hasMany(SquareImageItem::class);
    }
}
