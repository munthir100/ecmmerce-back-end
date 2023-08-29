<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Modules\Shipping\Entities\Country;

class StoreCountriesService
{
    public function getStoreCountries($store)
    {
        $countries = $store->countries->map(function ($country) {
            return [
                'name' => $country->name,
                'currency' => $country->currency_code,
            ];
        });

        return $countries;
    }

    public function createStoreCountry($store, $country, $data)
    {
        $store->countries()->attach($country);
    }

    public function checkIfCountryExestsInStore($store, $country)
    {
        if ($store->countries->contains($country)) {
            abort(response()->json('The country is already exists in thise store.', 404));
        }

        return true;
    }

    public function checkIfCountryNotExestsInStore($store, $country)
    {
        if (!$store->countries->contains($country)) {
            abort(response()->json('The country is not associated with the store.', 404));
        }
        return true;
    }
    public function checkIfCountryIsActivated($countries, $countryId)
    {
        $isActive =  $countries->where('countries.id', $countryId)
            ->where('store_countries.is_active', true)
            ->count() > 0;

        abort_if(!$isActive, response()->json('only activated countries can be set as default'));

        return true;
    }
    public function SetDefaultCountry($countries, $countryId)
    {
        $countries->update([
            'is_default' => DB::raw('CASE WHEN store_countries.country_id = ' . $countryId . ' THEN true ELSE false END')
        ]);
    }
    public function setDefaultStoreCurruncy($store, $curruncy)
    {
        $store->update(['default_currency' => $curruncy]);
    }
    public function checkIfCountryIsDefault($countries, $countryId)
    {
        $isDefault =  $countries->where('countries.id', $countryId)
            ->where('store_countries.is_default', true)
            ->count() > 0;
        abort_if($isDefault, response()->json('can not de activate or delete default country'));

        return true;
    }
}
