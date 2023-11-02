<?php

namespace Modules\Store\Http\Controllers;

use Modules\Store\Entities\Store;
use App\Http\Controllers\Controller;
use Modules\Admin\Transformers\CategoryResource;

class StoreBrandController extends Controller
{
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
