<?php

namespace Modules\Store\Entities;

use App\Filters\BrandFilters;
use App\Traits\HasUploads;
use App\Traits\Searchable;
use Essa\APIToolKit\Filters\Filterable;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model implements HasMedia
{
    use HasFactory, Filterable, InteractsWithMedia, HasUploads,SoftDeletes;

    protected $fillable = ['category_id', 'name', 'is_active'];

    protected string $default_filters = BrandFilters::class;

    protected $uploadMedia = [
        'image',
    ];

    function category()
    {
        return $this->belongsTo(Category::class);
    }
    // scopes
    public function scopeForAdmin($query, $adminId)
    {
        return $query->whereHas('category.store', function ($query) use ($adminId) {
            $query->where('id', $adminId);
        });
    }
}
