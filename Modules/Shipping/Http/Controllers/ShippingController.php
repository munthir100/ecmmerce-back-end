<?php

namespace Modules\Shipping\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Admin\Transformers\CityResource;
use Modules\Shipping\Entities\City;
use Modules\Shipping\Entities\Country;
use Modules\Shipping\Transformers\CountryResource;

class ShippingController extends Controller
{
    function countries()
    {
        $countries = Country::useFilters()->get();

        return $this->responseSuccess('countries',CountryResource::collection($countries));
    }

    function cities()
    {
        $cities = City::useFilters()->dynamicPaginate();

        return $this->responseSuccess('cities', CityResource::collection($cities));
    }
}
