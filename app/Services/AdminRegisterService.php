<?php

namespace App\Services;

use Illuminate\Support\Str;
use Modules\Acl\Entities\User;
use Illuminate\Support\Facades\Hash;
use Modules\Shipping\Entities\Country;

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
    public function createStore($admin, $userId, $storeName, $storeLink, $currencyCode)
    {

        $store = $admin->store()->create([
            'user_id' => $userId,
            'name' => $storeName,
            'link' => $storeLink,
            'store_theme_id' => 1,
            'currency_code' => $currencyCode
        ]);

        return $store;
    }
}
