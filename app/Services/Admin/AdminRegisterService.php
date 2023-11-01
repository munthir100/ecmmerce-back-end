<?php

namespace App\Services\Admin;


use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Modules\Acl\Entities\User;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Entities\Language;
use Modules\Shipping\Entities\Country;
use Modules\Store\Entities\StoreTheme;
use Spatie\Permission\Models\Permission;

class AdminRegisterService
{

    public function ValidPhoneForCountry($phone, $country)
    {
        if (!Str::startsWith($phone, $country->phone_code)) {
            abort(response()->json([
                'message' => 'is not a valid phone number for the selected country.',
                'success' => false,
                'statuscode' => Response::HTTP_CONFLICT,
            ]));
        }
        $phoneDigits = Str::length($phone) - Str::length($country->phone_code);

        if ($phoneDigits != $country->phone_digits_number) {
            abort(response()->json([
                'message' => 'The phone must have ' . $country->phone_digits_number . ' digits.',
                'success' => false,
                'statuscode' => Response::HTTP_CONFLICT,
            ]));
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

    public function setPermissionsToUser($user)
    {
        $permissions = Permission::all();
        $user->syncPermissions($permissions);
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
