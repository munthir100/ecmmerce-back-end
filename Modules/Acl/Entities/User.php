<?php

namespace Modules\Acl\Entities;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Modules\Admin\Entities\Admin;
use Modules\Admin\Entities\Seller;
use Spatie\Permission\Traits\HasRoles;
use Modules\Customer\Entities\Customer;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Acl\Database\Factories\UserFactory;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Subscription;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions, SoftDeletes, CascadeSoftDeletes;
    protected $guard_name = 'api';

    protected $cascadeDeletes = ['admin', 'customer', 'seller'];

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
        return $this->hasOne(Seller::class);
    }

    // attributes

    protected function IsAdmin(): Attribute
    {
        return Attribute::make()->get(fn () => $this->user_type_id == UserType::ADMIN);
    }
    protected function IsCustomer(): Attribute
    {
        return Attribute::make()->get(fn () => $this->user_type_id == UserType::CUSTOMER);
    }
    protected function IsSeller(): Attribute
    {
        return Attribute::make()->get(fn () => $this->user_type_id == UserType::SELLER);
    }
}
