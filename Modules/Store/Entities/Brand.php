<?php

namespace Modules\Store\Entities;

use App\Traits\HasUploads;
use App\Traits\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model implements HasMedia
{
    use HasFactory, Searchable, InteractsWithMedia, HasUploads;

    protected $fillable = ['category_id', 'name','is_active'];
    protected $searchable = ['name'];
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
