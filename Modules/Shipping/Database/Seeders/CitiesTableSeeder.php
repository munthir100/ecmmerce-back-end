<?php

namespace Modules\Shipping\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Modules\Shipping\Entities\Country;
use Illuminate\Database\Eloquent\Model;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("Modules/Shipping/Resources/cities.json");
        $citiesData = json_decode($json, true);

        foreach ($citiesData as $countryName => $cities) {
            $country = Country::where('name', $countryName)->first();

            foreach ($cities as $englishName => $arabicName) {
                $country->cities()->create([
                    'name' => $englishName,
                ]);
            }
        }
    }
}
