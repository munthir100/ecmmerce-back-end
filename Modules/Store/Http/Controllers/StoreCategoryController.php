<?php

namespace Modules\Store\Http\Controllers;

use Modules\Store\Entities\Store;
use App\Http\Controllers\Controller;
use Modules\Admin\Transformers\CategoryResource;

class StoreCategoryController extends Controller
{
    public function categories(Store $store)
    {
        $categories = $store->categories()->useFilters()->dynamicPaginate();

        return $this->responseSuccess(
            data: [
                'categories' => CategoryResource::collection($categories),
                'currency' => $store->default_currency,
            ],
        );
    }
}
