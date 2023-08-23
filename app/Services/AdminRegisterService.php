<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Acl\Entities\User;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Entities\Language;
use Modules\Admin\Entities\Status;
use Modules\Shipping\Entities\Country;
use Modules\Store\Entities\StoreTheme;

class AdminRegisterService
{

    public function findCountry($countryId)
    {
        $country = Country::find($countryId);
        if (!$country) {
            abort(response()->json('Invalid country selected.'));
        }
        return $country;
    }
    public function ValidPhoneForCountry($phone, $country)
    {
        if (!Str::startsWith($phone, $country->phone_code)) {
            abort(response()->json('is not a valid phone number for the selected country.'));
        }
        $phoneDigits = Str::length($phone) - Str::length($country->phone_code);

        if ($phoneDigits != $country->phone_digits_number) {
            abort(response()->json('The phone must have ' . $country->phone_digits_number . ' digits.'));
        }
    }

    public function createUser($name, $email, $phone, $password, $countryId)
    {
        $user = User::create([
            'user_type_id' => 1,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => Hash::make($password),
            'country_id' => $countryId
        ]);

        return $user;
    }
    public function createStore($admin, $storeName, $storeLink, $currencyCode)
    {

        $store = $admin->store()->create([
            'name' => $storeName,
            'link' => $storeLink,
            'default_currency' => $currencyCode,
            'language_id' => Language::ARABIC,
            'store_theme_id' => StoreTheme::DEFAULT,
        ]);

        return $store;
    }

    public function createDefaultStoreCountry($store, $country)
    {
        $store->countries()->attach($country, [
            'is_default' => true,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
