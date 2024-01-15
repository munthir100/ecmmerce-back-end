<?php

namespace Modules\Shipping\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Admin\Transformers\CityResource;
use Modules\Shipping\Entities\City;
use Modules\Shipping\Entities\Country;
use Modules\Shipping\Transformers\CountryResource;

class ShippingController extends Controller
{
    function countries()
    {
        $countries = Country::useFilters()->dynamicPaginate();

        return $this->responseSuccess(data: [CountryResource::collection($countries)]);
    }

    function cities()
    {
        $cities = City::useFilters()->dynamicPaginate();

        return $this->responseSuccess(data: [CityResource::collection($cities)]);
    }
}
