<?php

namespace Modules\Shipping\Entities;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory,Searchable,SoftDeletes;

    protected $searchable = ['name'];
    protected $fillable = ['name','country_id'];

    public function captains()
    {
        return $this->belongsToMany(Captain::class);
    }
}
