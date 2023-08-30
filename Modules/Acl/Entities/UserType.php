<?php

namespace Modules\Acl\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserType extends Model
{
    use HasFactory,SoftDeletes;
    
    const ADMIN = 1;
    const CUSTOMER = 2;
    const SELLER = 3;

    protected $fillable = ['name'];

    function user()
    {
        return $this->hasOne(User::class);
    }
}
