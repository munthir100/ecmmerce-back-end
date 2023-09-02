<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\StoreService;
use App\Http\Controllers\Controller;
use Modules\Admin\Transformers\StoreResource;
use Modules\Admin\Http\Requests\UpdateStoreRequest;

class StoreAdditionalSettingsController extends Controller
{
    protected $storeService, $store;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
        $this->store = $this->storeService->getStore();
    }

    public function updateCommercialRegistration(UpdateStoreRequest $request)
    {
        $data = $request->validateCommercialRegistration($this->store);
        $this->store->update($data);

        return $this->responseSuccess('Commercial registration number updated', new StoreResource($this->store));
    }

    public function updateStoreLanguage(UpdateStoreRequest $request)
    {
        $data = $request->validated();
        $this->store->update($data);

        return $this->responseSuccess('Language updated', new StoreResource($this->store));
    }

    public function updateStatus(UpdateStoreRequest $request)
    {
        $data = $request->validated();
        $this->store->update($data);

        return $this->responseSuccess('Store status updated.', new StoreResource($this->store));
    }

    public function updateColors(UpdateStoreRequest $request)
    {
        $validatedData = $request->validated();

        $this->store->update($validatedData);

        return $this->responseSuccess('Store colors updated.', new StoreResource($this->store));
    }

}
