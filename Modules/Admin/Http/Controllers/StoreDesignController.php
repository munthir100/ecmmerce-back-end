<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Responses\MessageResponse;
use Essa\APIToolKit\Api\ApiResponse;
use Modules\Admin\Transformers\StoreResource;
use Modules\Admin\Http\Requests\UpdateStoreNavbarRequest;
use Modules\Admin\Http\Requests\UpdateStoreThemeRequest;

class StoreDesignController extends Controller
{
    use ApiResponse;
    public function updateNavbar(UpdateStoreNavbarRequest $request)
    {
        $store = $this->getStoreFromRequestUser();
        $validatedData = $request->validated();
        $store->update($validatedData);

        return $this->responseSuccess('Store navbar updated.',new StoreResource($store));
    }

    public function updateTheme(UpdateStoreThemeRequest $request)
    {
        $store = $this->getStoreFromRequestUser();
        $validatedData = $request->validated();
        $store->update($validatedData);

        return $this->responseSuccess('Store theme updated.',new StoreResource($store));
    }

    private function getStoreFromRequestUser()
    {
        return request()->user()->admin->store;
    }
}
