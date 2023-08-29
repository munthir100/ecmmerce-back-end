<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Essa\APIToolKit\Api\ApiResponse;
use Modules\Admin\Transformers\StoreResource;
use Modules\Admin\Http\Requests\UpdateStoreRequest;

class StoreAdditionalSettingsController extends Controller
{
    use ApiResponse;
    public function updateCommercialRegistration(UpdateStoreRequest $request)
    {
        $store = $this->getStoreFromRequestUser();
        $data = $request->validateCommercialRegistration($store);
        $store->update($data);

        return $this->responseSuccess('Commercial registration number updated',new StoreResource($store));
    }

    public function updateStoreLanguage(UpdateStoreRequest $request)
    {
        $store = $this->getStoreFromRequestUser();
        $data = $request->validated();
        $store->update($data);

        return $this->responseSuccess('Language updated', new StoreResource($store));
    }

    public function updateStatus(UpdateStoreRequest $request)
    {
        $store = $this->getStoreFromRequestUser();
        $data = $request->validated();
        $store->update($data);

        return $this->responseSuccess('Store status updated.',new StoreResource($store));
    }

    public function updateColors(UpdateStoreRequest $request)
    {
        $store = $this->getStoreFromRequestUser();
        $validatedData = $request->validated();

        $store->update($validatedData);

        return $this->responseSuccess('Store colors updated.',new StoreResource($store));
    }



    private function getStoreFromRequestUser()
    {
        return request()->user()->admin->store;
    }
}
