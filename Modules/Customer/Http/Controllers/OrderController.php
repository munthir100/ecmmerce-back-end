<?php

namespace Modules\Customer\Http\Controllers;

use Essa\APIToolKit\Api\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Customer\Transformers\OrderResource;
use Modules\Store\Entities\Store;

class OrderController extends Controller
{
    public function index(Store $store)
    {
        $customerOrders = auth()->user()->customer->orders()->useFilters()->with('items')->dynamicPaginate();

        return $this->responseSuccess(data: OrderResource::collection($customerOrders));
    }

    public function invoice(){
        
    }
}
