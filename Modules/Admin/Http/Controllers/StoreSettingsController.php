<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\StoreService;
use Illuminate\Routing\Controller;
use Modules\Admin\Transformers\StoreResource;
use Modules\Admin\Http\Requests\UpdateStoreRequest;

class StoreSettingsController extends Controller
{
    protected $storeService,$store;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
        $this->store = $this->storeService->getStore();
    }
    function updateBasicInformation(UpdateStoreRequest $request)
    {
        $data = $request->validated();
        $data += $request->validateStoreLink($this->store);
        $updatedStore = $this->store->update($data);

        return $this->responseSuccess('store data updated', new StoreResource($updatedStore));
    }

    function UpdateStoreLogo(UpdateStoreRequest $request)
    {
        $request->validated();

        if ($request->has('store_logo')) {
            $this->store->clearMediaCollection('store_logo');
            $this->store->addMediaFromRequest('store_logo')->toMediaCollection('store_logo');
        }

        return $this->responseSuccess('store logo updated');
    }
    function UpdateStoreIcon(UpdateStoreRequest $request)
    {
        $request->validated();

        if ($request->has('store_icon')) {
            $this->store->clearMediaCollection('store_icon');
            $this->store->addMediaFromRequest('store_icon')->toMediaCollection('store_icon');
        }

        return $this->responseSuccess('store icon updated');
    }

    function UpdateStoreCity(UpdateStoreRequest $request)
    {
        $data = $request->validateStoreCity($this->store);
        $this->store->update($data);

        return $this->responseSuccess('store city updated', $data);
    }
    
}
