<?php

namespace Modules\Shipping\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Shipping\Entities\City;
use App\Http\Responses\MessageResponse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Admin\Transformers\CityResource;

class ShippingController extends Controller
{
    function cities()
    {
        $term = request()->get('term', '');
        $perPage = request()->get('perPage', 25);
        $cities = City::search($term)->paginate($perPage);

        return new MessageResponse(
            data: ['products' => CityResource::collection($cities)],
            statusCode: 200
        );
    }
}
