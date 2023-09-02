<?php

namespace Modules\Store\Entities;

use App\Traits\HasUploads;
use App\Filters\ProductFilters;
use Spatie\MediaLibrary\HasMedia;
use willvincent\Rateable\Rateable;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\ProductOption;
use Modules\Customer\Entities\ShoppingCart;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Entities\OrderItem;

class Product extends Model  implements HasMedia
{
    use HasFactory, Filterable, InteractsWithMedia, HasUploads, Rateable, SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = [
        'options',
    ];

    protected $fillable = [
        'category_id',
        'store_id',
        'name',
        'sku',
        'quantity',
        'wheight',
        'short_description',
        'description',
        'price',
        'cost',
        'is_discounted',
        'free_shipping',
        'is_active',
    ];
    protected string $default_filters = ProductFilters::class;

    protected $uploadMedia = [
        'main_image',
        'sub_images',
    ];
    function store()
    {
        return $this->belongsTo(Store::class);
    }
    function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function shoppingCarts()
    {
        return $this->belongsToMany(ShoppingCart::class);
    }
    public function options()
    {
        return $this->hasMany(ProductOption::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    //scopes
    public function scopeForAdmin($query, $adminId)
    {
        return $query->whereHas('store.admin', function ($query) use ($adminId) {
            $query->where('id', $adminId);
        });
    }

    public function scopeWithStoreCurrencyCode($query)
    {
        return $query->addSelect([
            'default_currency' => Store::select('default_currency')
                ->whereColumn('id', 'products.store_id')
                ->limit(1)
        ]);
    }
    public function scopeDiscounted($query)
    {
        return $query->where('is_discounted', true);
    }

    public function scopeFreeShipping($query)
    {
        return $query->where('free_shipping', true);
    }


    // attributes

    protected function FeaturedProdcut(): Attribute
    {
        return Attribute::make()->get(fn () => $this->options()->exists());
    }
}
