<?php

namespace Modules\Admin\Entities;

use App\Filters\CouponFilters;
use App\Traits\ModelsForStore;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Essa\APIToolKit\Filters\Filterable;
use Modules\Store\Entities\Store;
use Illuminate\Database\Eloquent\Model;
use Modules\Customer\Entities\CouponUsage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory,Filterable,ModelsForStore,SoftDeletes,CascadeSoftDeletes;
    
    protected $cascadeDeletes = [
        'usages',
    ];

    protected string $default_filters = CouponFilters::class;


    protected $fillable = [
        'promocode',
        'discount_type', // ['percentage', 'fixed']
        'value', // the value of discount
        'discount_end_date',
        'exclude_discounted_products', // boolean
        'minimum_purchase',
        'total_usage_times',
        'usage_per_customer',
        'used_times',
        'store_id',
        'is_active'
    ];
    function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }
    // scpoes
    public function scopeForAdmin($query, $adminId)
    {
        return $query->whereHas('store.admin', function ($query) use ($adminId) {
            $query->where('id', $adminId);
        });
    }
}
