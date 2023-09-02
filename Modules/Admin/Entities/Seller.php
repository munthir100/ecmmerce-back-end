<?php

namespace Modules\Admin\Entities;

use App\Filters\SellerFilters;
use Essa\APIToolKit\Filters\Filterable;
use Modules\Acl\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Store\Entities\Store;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Seller extends Model
{
    use HasFactory,Filterable,SoftDeletes;

    protected string $default_filters = SellerFilters::class;
    
    protected $fillable = ['user_id','admin_id','store_id'];

    function user()
    {
        return $this->belongsTo(User::class);
    }
    function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    function store()
    {
        return $this->belongsTo(Store::class);
    }
    function role()
    {
        return $this->hasMany(Role::class);
    }
    function permissions()
    {
        return $this->hasMany(Permission::class);
    }
    // scopes

    public function scopeForAdmin($query, $adminId)
    {
        return $query->whereHas('admin', function ($query) use ($adminId) {
            $query->where('id', $adminId);
        });
    }
}
