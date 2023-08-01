<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SquareImage extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public function items()
    {
        return $this->hasMany(SquareImageItem::class);
    }
}
