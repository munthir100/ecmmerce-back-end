<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\StoreService;
use Illuminate\Routing\Controller;
use Modules\Shipping\Entities\City;
use Essa\APIToolKit\Api\ApiResponse;
use Modules\Admin\Transformers\CityResource;

class StoreCitiesController extends Controller
{
    protected $storeService,$store;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
        $this->store = $this->storeService->getStore();
    }
    public function index()
    {
        $cities = City::whereIn('country_id', function ($query){
            $query->select('country_id')
                ->from('store_countries')
                ->where('store_id', $this->store->id);
        })
        ->dynamicPaginate();

        return $this->responseSuccess(data:['cities' => CityResource::collection($cities)]);
    }

}
