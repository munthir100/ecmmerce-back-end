<?php

namespace Modules\Admin\Entities;

use Modules\Acl\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Store\Entities\Store;

class Admin extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['user_id'];

    function user()
    {
        return $this->belongsTo(User::class);
    }
    function store()
    {
        return $this->hasOne(Store::class);
    }
    function sellers()
    {
        return $this->hasMany(Seller::class);
    }
    function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }
}
