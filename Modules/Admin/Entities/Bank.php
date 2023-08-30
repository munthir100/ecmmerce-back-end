<?php

namespace Modules\Admin\Entities;

use App\Traits\HasUploads;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasUploads,SoftDeletes;

    protected $fillable = ['name'];
    protected $uploadMedia = ['bank_logo'];
    
    function accounts()
    {
        return $this->hasMany(BankAccount::class);
    }
}
