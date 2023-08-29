<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Store\Entities\Store;
use Illuminate\Routing\Controller;
use Modules\Store\Entities\Category;
use App\Http\Responses\MessageResponse;
use Essa\APIToolKit\Api\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Admin\Transformers\CategoryResource;

class StoreCategoryController extends Controller
{
    use ApiResponse;
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
