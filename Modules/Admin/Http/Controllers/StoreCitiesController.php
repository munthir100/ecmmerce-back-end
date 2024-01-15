<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Shipping\Entities\City;
use Modules\Admin\Transformers\CityResource;

class StoreCitiesController extends Controller
{

    public function index()
    {
        $cities = City::whereIn('country_id', function ($query){
            $query->select('country_id')
                ->from('store_countries')
                ->where('store_id', request()->store->id);
        })
        ->useFilters()
        ->dynamicPaginate();

        return $this->responseSuccess(data:[CityResource::collection($cities)]);
    }

}
