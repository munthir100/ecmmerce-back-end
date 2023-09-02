<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\StoreService;
use Illuminate\Routing\Controller;
use Modules\Admin\Transformers\StoreResource;
use Modules\Admin\Http\Requests\UpdateStoreThemeRequest;
use Modules\Admin\Http\Requests\UpdateStoreNavbarRequest;

class StoreDesignController extends Controller
{
    protected $storeService, $store;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
        $this->store = $this->storeService->getStore();
    }

    public function updateNavbar(UpdateStoreNavbarRequest $request)
    {
        
        $validatedData = $request->validated();
        $updatedStore = $this->store->update($validatedData);

        return $this->responseSuccess('Store navbar updated.', new StoreResource($updatedStore));
    }

    public function updateTheme(UpdateStoreThemeRequest $request)
    {
        
        $validatedData = $request->validated();
        $updatedStore = $this->store->update($validatedData);

        return $this->responseSuccess('Store theme updated.', new StoreResource($updatedStore));
    }
}
