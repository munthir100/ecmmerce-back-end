<?php

namespace Modules\Acl\Entities;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Modules\Admin\Entities\Admin;
use Spatie\Permission\Traits\HasRoles;
use Modules\Customer\Entities\Customer;
use Illuminate\Notifications\Notifiable;
use Modules\Acl\Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    protected $fillable = [
        'user_type_id',
        'country_id',
        'name',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    function admin()
    {
        return $this->hasOne(Admin::class);
    }
    function customer()
    {
        return $this->hasOne(Customer::class);
    }
    function seller()
    {
        return $this->hasOne(Sller::class);
    }
}
