<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialMediaLink extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'facebook',
        'snapchat',
        'twitter',
        'tictok',
        'whatsapp',
        'maroof',
        'instagram',
        'telegram',
        'google_play',
        'app_store',
        'store_id',
    ];
    function store()
    {
        return $this->belongsTo(Store::class);
    }
}
