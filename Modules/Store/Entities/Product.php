<?php

namespace Modules\Store\Entities;

use App\Traits\HasUploads;
use App\Traits\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\ProductOption;
use Modules\Customer\Entities\ShoppingCart;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model  implements HasMedia
{
    use HasFactory, Searchable, InteractsWithMedia, HasUploads;

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
        'discount',
        'free_shipping',
        'is_active',
    ];

    protected $searchable = ['name', 'short_description'];
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
    //scopes
    public function scopeForAdmin($query, $adminId)
    {
        return $query->whereHas('store.admin', function ($query) use ($adminId) {
            $query->where('id', $adminId);
        });
    }

    // attributes

    protected function FeaturedProdcut(): Attribute
    {
        return Attribute::make()->get(fn () => $this->options()->exists());
    }
}
