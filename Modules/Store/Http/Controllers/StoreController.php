<?php

namespace Modules\Store\Http\Controllers;

use Modules\Store\Entities\Store;

use Modules\Shipping\Entities\City;
use App\Http\Controllers\Controller;
use Modules\Admin\Transformers\CityResource;
use Modules\Store\Http\Requests\RatingRequest;

class StoreController extends Controller
{
    function cities(Store $store)
    {
        $cities = City::whereIn('country_id', function ($query) use($store){
            $query->select('country_id')
                ->from('store_countries')
                ->where('store_id', $store->id);
        })
        ->dynamicPaginate();

        return $this->responseSuccess(data:[CityResource::collection($cities)]);
    }

    function ratings(Store $store)
    {
        $data = [
            'ratings' => $store->ratings,
            'store_rating' => $store->averageRating,
        ];

        return $this->responseSuccess('rating', $data, 200);
    }

    function rate(Store $store, RatingRequest $request)
    {
        $data = $request->validated();
        $store->rateOnce($data['rating']);

        return $this->responseSuccess('thank,s for your feedback');
    }
}
