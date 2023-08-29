<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use App\Http\Responses\MessageResponse;
use Essa\APIToolKit\Api\ApiResponse;
use Modules\Admin\Transformers\StoreResource;
use Modules\Admin\Http\Requests\UpdateStoreRequest;

class StoreSettingsController extends Controller
{
    use ApiResponse;
    function updateBasicInformation(UpdateStoreRequest $request)
    {
        $data = $request->validated();
        $store = $this->getStoreFromRequestUser();
        $data += $request->validateStoreLink($store);
        $store->update($data);

        return $this->responseSuccess('store data updated', new StoreResource($store));
    }

    function UpdateStoreLogo(UpdateStoreRequest $request)
    {
        $request->validated();
        $store = $this->getStoreFromRequestUser();

        if ($request->has('store_logo')) {
            $store->clearMediaCollection('store_logo');
            $store->addMediaFromRequest('store_logo')->toMediaCollection('store_logo');
        }

        return $this->responseSuccess('store logo updated', new StoreResource($store));
    }
    function UpdateStoreIcon(UpdateStoreRequest $request)
    {
        $request->validated();
        $store = $this->getStoreFromRequestUser();

        if ($request->has('store_icon')) {
            $store->clearMediaCollection('store_icon');
            $store->addMediaFromRequest('store_icon')->toMediaCollection('store_icon');
        }

        return $this->responseSuccess('store icon updated', new StoreResource($store));
    }

    function UpdateStoreCity(UpdateStoreRequest $request)
    {
        $store = $this->getStoreFromRequestUser();
        $data = $request->validateStoreCity($store);
        $store->update($data);

        return $this->responseSuccess('store city updated', new StoreResource($store));
    }
    
    private function getStoreFromRequestUser()
    {
        return request()->user()->admin->store;
    }
}
