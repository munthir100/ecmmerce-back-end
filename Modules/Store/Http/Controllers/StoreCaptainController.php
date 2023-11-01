<?php

namespace Modules\Store\Http\Controllers;

use Modules\Store\Entities\Store;
use Illuminate\Routing\Controller;
use Modules\Admin\Transformers\CaptainResource;

class StoreCaptainController extends Controller
{
    function captains(Store $store)
    {
        $perPage = request()->query('PerPage', 25);
        $captains = $store->captains()->where('is_active', true)->paginate($perPage);

        return $this->responseSuccess('captains', CaptainResource::collection($captains), 200);
    }
}
