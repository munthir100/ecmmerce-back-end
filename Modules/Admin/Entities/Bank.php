<?php

namespace Modules\Admin\Entities;

use App\Traits\HasUploads;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bank extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasUploads, SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = [
        'accounts',
    ];

    protected $fillable = ['name'];
    protected $uploadMedia = ['bank_logo'];

    function accounts()
    {
        return $this->hasMany(BankAccount::class);
    }
}
