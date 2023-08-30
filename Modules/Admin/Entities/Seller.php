<?php

namespace Modules\Admin\Entities;

use App\Filters\SellerFilters;
use App\Traits\Searchable;
use Essa\APIToolKit\Filters\Filterable;
use Modules\Acl\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Store\Entities\Store;

class Seller extends Model
{
    use HasFactory,Filterable,SoftDeletes;

    protected string $default_filters = SellerFilters::class;
    
    protected $fillable = ['user_id','admin_id'];

    function user()
    {
        return $this->belongsTo(User::class);
    }
    function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    // scopes

    public function scopeForAdmin($query, $adminId)
    {
        return $query->whereHas('admin', function ($query) use ($adminId) {
            $query->where('id', $adminId);
        });
    }
}
