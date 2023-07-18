<?php

namespace Modules\Admin\Entities;

use Modules\Acl\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','admin_id'];

    function user()
    {
        return $this->belongsTo(User::class);
    }
    function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
