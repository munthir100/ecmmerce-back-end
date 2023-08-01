<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class YoutubeVedio extends Model
{
    use HasFactory;

    protected $fillable = ['link'];
    

}
