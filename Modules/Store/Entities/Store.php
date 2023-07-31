<?php

namespace Modules\Store\Entities;

use App\Traits\HasUploads;
use Modules\Admin\Entities\Admin;
use Spatie\MediaLibrary\HasMedia;
use Modules\Shipping\Entities\City;
use Modules\Shipping\Entities\Captain;
use Illuminate\Database\Eloquent\Model;
use Modules\Customer\Entities\Customer;
use Modules\Store\Entities\SocialMediaLink;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasUploads;

    protected $fillable = ['admin_id', 'name', 'link'];

    protected $uploadMedia = [
        'store_logo',
        'store_icon',
    ];

    function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
    function categories()
    {
        return $this->hasMany(Category::class);
    }
    function products()
    {
        return $this->hasMany(Product::class);
    }
    function captains()
    {
        return $this->hasMany(Captain::class);
    }
    function city()
    {
        return $this->belongsTo(City::class);
    }
    function socialMediaLinks()
    {
        return $this->hasOne(SocialMediaLink::class);
    }
}
