<?php

namespace Modules\Admin\Entities;

use Modules\Acl\Entities\User;
use Modules\Store\Entities\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    use HasFactory,SoftDeletes,CascadeSoftDeletes;

    protected $cascadeDeletes = ['store','sellers','bankAccounts','notifications'];

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
    function notifications()
    {
        return $this->hasMany(AdminNotification::class);
    }
    function contactMessages()
    {
        return $this->hasMany(ContactMessage::class);
    }
}
