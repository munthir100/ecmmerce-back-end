<?php

namespace Modules\Admin\Http\Controllers;

use Essa\APIToolKit\Api\ApiResponse;
use Illuminate\Routing\Controller;
use Modules\Admin\Transformers\CityResource;
use Modules\Shipping\Entities\City;

class StoreCitiesController extends Controller
{
    use ApiResponse;
    public function index()
    {
        $storeId = auth()->user()->admin->store->id;

        $cities = City::whereIn('country_id', function ($query) use ($storeId) {
            $query->select('country_id')
                ->from('store_countries')
                ->where('store_id', $storeId);
        })
        ->dynamicPaginate();

        return $this->responseSuccess(data:['cities' => CityResource::collection($cities)]);
    }

}
