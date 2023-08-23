<?php

namespace Modules\Store\Entities;

use Modules\Store\Entities\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreTheme extends Model
{
    use HasFactory;

    protected $fillable = ['name','price'];

    const DEFAULT = 1;
    const FIRST = 1;
    
    public function stores()
    {
        return $this->hasMany(Store::class);
    }
}
