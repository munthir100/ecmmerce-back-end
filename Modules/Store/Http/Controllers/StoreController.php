<?php

namespace Modules\Store\Http\Controllers;

use Modules\Store\Entities\Store;
use Illuminate\Routing\Controller;
use Modules\Store\Http\Requests\RatingRequest;

class StoreController extends Controller
{
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
