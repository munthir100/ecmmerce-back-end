<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Customer\Transformers\OrderResource;
use Modules\Store\Entities\Store;

class OrderController extends Controller
{
    public function index(Store $store)
    {
        $customerOrders = request()->user()->customer->orders()->useFilters()->with('items')->dynamicPaginate();

        return $this->responseSuccess(data: [OrderResource::collection($customerOrders)]);
    }

    public function invoice()
    {
    }
}
