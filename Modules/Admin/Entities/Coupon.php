<?php

namespace Modules\Admin\Entities;

use App\Traits\Searchable;
use Modules\Store\Entities\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory,Searchable;
    
    protected $searchable = ['promocode'];

    protected $fillable = [
        'promocode',
        'discount_type',
        'value',
        'discount_end_date',
        'exclude_discounted_products',
        'minimum_purchase',
        'total_usage_times',
        'usage_per_customer',
        'store_id',
    ];
    function store()
    {
        return $this->belongsTo(Store::class);
    }

    // scpoes
    public function scopeForAdmin($query, $adminId)
    {
        return $query->whereHas('store.admin', function ($query) use ($adminId) {
            $query->where('id', $adminId);
        });
    }
}
