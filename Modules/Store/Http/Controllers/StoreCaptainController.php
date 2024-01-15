<?php

namespace Modules\Store\Http\Controllers;

use Modules\Store\Entities\Store;
use App\Http\Controllers\Controller;
use Modules\Admin\Transformers\CaptainResource;

class StoreCaptainController extends Controller
{
    function captains(Store $store)
    {
        $captains = $store->captains()->where('is_active', true)->dynamicPaginate();

        return $this->responseSuccess(data: [CaptainResource::collection($captains)]);
    }
}
