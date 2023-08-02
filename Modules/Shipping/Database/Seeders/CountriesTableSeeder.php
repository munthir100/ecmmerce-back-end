<?php

namespace Modules\Shipping\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Modules\Shipping\Entities\Country;
use Illuminate\Database\Eloquent\Model;


class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("Modules/Shipping/Resources/countries.json");
        $countries = json_decode($json, true);

        foreach ($countries as $countryData) {
            Country::create([
                'name' => $countryData['name'],
                'phone_code' => $countryData['phone_code'],
                'phone_digits_number' => $countryData['phone_digits_number'],
                'currency_code' => $countryData['currency_code'],
            ]);
        }
    }
}
