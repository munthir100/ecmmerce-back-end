<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Store\Entities\Store;
use Illuminate\Routing\Controller;
use App\Http\Responses\MessageResponse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Admin\Transformers\CaptainResource;

class StoreCaptainController extends Controller
{
    function captains(Store $store)
    {
        $perPage = request()->query('PerPage', 25);
        $captains = $store->captains()->where('is_active', true)->paginate($perPage);

        return new MessageResponse('captains', CaptainResource::collection($captains), 200);
    }
}
