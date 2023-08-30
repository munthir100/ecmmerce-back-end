<?php

namespace Modules\Store\Entities;

use App\Filters\CategoryFilters;
use App\Traits\HasUploads;
use App\Traits\Searchable;
use Essa\APIToolKit\Filters\Filterable;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model  implements HasMedia
{
    use HasFactory, Filterable, InteractsWithMedia, HasUploads, SoftDeletes;

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
        return $this->hasMany(Product::class)->isActive();
    }
    public function brands()
    {
        return $this->hasMany(Brand::class)->isActive();
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->isActive();
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id')->isActive();
    }
    //scopes
    public function scopeForAdmin($query, $adminId)
    {
        return $query->whereHas('store.admin', function ($query) use ($adminId) {
            $query->where('id', $adminId);
        });
    }
}
