<?php

namespace Modules\Admin\Entities;

use Modules\Acl\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Store\Entities\Store;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    function user()
    {
        return $this->belongsTo(User::class);
    }
    function store()
    {
        return $this->hasOne(Store::class);
    }
    function seller()
    {
        return $this->hasOne(Seller::class);
    }
}
