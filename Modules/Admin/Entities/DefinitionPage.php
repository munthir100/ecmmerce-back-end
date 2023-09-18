<?php

namespace Modules\Admin\Entities;

use App\Filters\DefinitionPageFilters;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DefinitionPage extends Model
{
    use HasFactory,SoftDeletes,Filterable;

    protected string $default_filters = DefinitionPageFilters::class;

    protected $fillable = ['store_id', 'title', 'description', 'is_active'];

    public function store()
    {
        return $this->belongsTo(Store::class, "store_id");
    }
}
