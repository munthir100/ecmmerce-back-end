<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SquareImageItem extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        "square_image_id",
        "path",
        "path_type"
    ];
    public function squareImage()
    {
        return $this->belongsTo(SquareImage::class);
    }
}
