<?php

namespace Modules\Store\Entities;

use App\Traits\HasUploads;
use Laravel\Cashier\Billable;
use Modules\Admin\Entities\Tax;
use Laravel\Cashier\Subscription;
use Modules\Admin\Entities\Admin;
use Spatie\MediaLibrary\HasMedia;
use Modules\Admin\Entities\Coupon;
use willvincent\Rateable\Rateable;
use Modules\Shipping\Entities\City;
use Modules\Customer\Entities\Order;
use Modules\Shipping\Entities\Captain;
use Modules\Shipping\Entities\Country;
use Illuminate\Database\Eloquent\Model;
use Modules\Customer\Entities\Customer;
use Modules\Admin\Entities\DefinitionPage;
use Modules\Store\Entities\SocialMediaLink;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Store\Entities\AdditionalSetting;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasUploads, Rateable, SoftDeletes, CascadeSoftDeletes,Billable;

    protected $cascadeDeletes = [
        'customers',
        'categories',
        'products',
        'brands',
        'coupons',
        'captains',
        'additionalSettings',
        'socialMediaLinks',
        'taxes',
    ];

    protected $fillable = [
        'admin_id',
        'store_theme_id',
        'name',
        'link',
        'default_currency',
        'status_id',
        'is_active',
        'maintenance_message',
        'city_id',
        'commercial_registration_no',
        'language_id',
        'button_color',
        'text_color',
        'banner_color',
        'banner_content',
        'banner_link',
    ];

    protected $uploadMedia = [
        'store_logo',
        'store_icon',
    ];

    function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    function theme()
    {
        return $this->belongsTo(StoreTheme::class);
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
    function brands()
    {
        return $this->hasMany(Brand::class);
    }
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
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
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'store_countries');
    }
    public function additionalSettings()
    {
        return $this->hasMany(AdditionalSetting::class);
    }
    function orders()
    {
        return $this->hasMany(Order::class);
    }
    function taxes()
    {
        return $this->hasMany(Tax::class);
    }
    function definitionPages()
    {
        return $this->hasMany(DefinitionPage::class);
    }
    function subscription()
    {
        return $this->hasOne(Subscription::class);
    }
}
