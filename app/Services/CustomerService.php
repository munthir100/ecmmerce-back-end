<?php

namespace App\Services;

use App\Services\CartService;
use Modules\Store\Entities\Store;
use Modules\Shipping\Entities\City;
use Modules\Customer\Entities\Order;
use Modules\Customer\Entities\Customer;
use Illuminate\Support\Facades\Response;
use Modules\Customer\Entities\OrderItem;
use Modules\Customer\Transformers\shoppingCartResource;

class CustomerService
{
    function findCustomerModel(Customer $customer,$modelClass, $modelId)
    {
        $model = $modelClass::where('customer_id',$customer->id)->find($modelId);
        if (!$model) {
            $modelName = class_basename($modelClass);
            abort(response()->json([
                'message' => $modelName . ' not found',
                'success' => false,
                'statuscode' => 404,
            ]));
        }

        return $model;
    }
    function findModel(Customer $customer,$modelClass)
    {
        $model = $modelClass::where('customer_id',$customer->id)->first();
        if (!$model) {
            $modelName = class_basename($modelClass);
            abort(response()->json([
                'message' => $modelName . ' not found',
                'success' => false,
                'statuscode' => 404,
            ]));
        }

        return $model;
    }
}
