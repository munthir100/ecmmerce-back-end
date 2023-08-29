<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Store\Entities\Brand;
use Modules\Store\Entities\Store;
use Illuminate\Routing\Controller;
use Essa\APIToolKit\Api\ApiResponse;
use App\Http\Responses\MessageResponse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Admin\Transformers\CategoryResource;

class StoreBrandController extends Controller
{
    use ApiResponse;
    public function brands(Store $store)
    {
        $brands = $store->brands()->useFilters()->dynamicPaginate();

        return $this->responseSuccess(
            data: [
                'brands' => CategoryResource::collection($brands),
                'currency' => $store->default_currency,
            ],
        );
    }
}
