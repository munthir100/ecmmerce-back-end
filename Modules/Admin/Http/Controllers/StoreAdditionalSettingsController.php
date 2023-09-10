<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\StoreService;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Admin\Transformers\StoreResource;
use Modules\Admin\Http\Requests\UpdateStoreRequest;

class StoreAdditionalSettingsController extends Controller
{
    use AuthorizesRequests;
    protected $storeService, $store;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
        $this->store = $this->storeService->getStore();
    }

    public function updateCommercialRegistration(UpdateStoreRequest $request)
    {
        $this->authorize('Manage-Store-Settings');
        $data = $request->validateCommercialRegistration($this->store);
        $this->store->update($data);

        return $this->responseSuccess('Commercial registration number updated', new StoreResource($this->store));
    }

    public function updateStoreLanguage(UpdateStoreRequest $request)
    {
        $this->authorize('Manage-Store-Settings');
        $data = $request->validated();
        $this->store->update($data);

        return $this->responseSuccess('Language updated', new StoreResource($this->store));
    }

    public function updateStatus(UpdateStoreRequest $request)
    {
        $this->authorize('Manage-Store-Settings');
        $data = $request->validated();
        $this->store->update($data);

        return $this->responseSuccess('Store status updated.', new StoreResource($this->store));
    }

}
