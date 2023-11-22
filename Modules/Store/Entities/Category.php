<?php

namespace Modules\Store\Entities;

use App\Traits\HasUploads;
use App\Traits\Searchable;
use App\Filters\CategoryFilters;
use Spatie\MediaLibrary\HasMedia;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model  implements HasMedia
{
    use HasFactory, Filterable, InteractsWithMedia, HasUploads, SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = [
        'brands',
        'children',
    ];
    protected $fillable = ['store_id', 'name', 'parent_id', 'is_active'];

    protected string $default_filters = CategoryFilters::class;

    protected $uploadMedia = [
        'image',
    ];

    function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function brands()
    {
        return $this->hasMany(Brand::class);
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    //scopes
    public function scopeForAdmin($query, $adminId)
    {
        return $query->whereHas('store.admin', function ($query) use ($adminId) {
            $query->where('id', $adminId);
        });
    }
}
