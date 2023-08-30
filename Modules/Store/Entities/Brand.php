<?php

namespace Modules\Store\Entities;

use App\Traits\HasUploads;
use App\Traits\Searchable;
use App\Filters\BrandFilters;
use Spatie\MediaLibrary\HasMedia;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model implements HasMedia
{
    use HasFactory, Filterable, InteractsWithMedia, HasUploads, SoftDeletes;

    protected $fillable = ['category_id', 'name', 'is_active'];

    protected string $default_filters = BrandFilters::class;

    protected $uploadMedia = [
        'image',
    ];

    function category()
    {
        return $this->belongsTo(Category::class);
    }
    function products()
    {
        return $this->hasMany(Product::class);
    }
    // scopes
    public function scopeForAdmin($query, $adminId)
    {
        return $query->whereHas('category.store', function ($query) use ($adminId) {
            $query->where('id', $adminId);
        });
    }
}
