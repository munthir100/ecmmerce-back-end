<?php

namespace Modules\Admin\Entities;

use App\Traits\Searchable;
use App\Traits\ModelsForStore;
use Modules\Store\Entities\Store;
use Illuminate\Database\Eloquent\Model;
use Modules\Customer\Entities\CouponUsage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory,Searchable,ModelsForStore;
    
    protected $searchable = ['promocode'];

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
    ];
    function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function couponUsages()
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
