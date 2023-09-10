<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\StoreService;
use Illuminate\Routing\Controller;
use Modules\Admin\Transformers\StoreResource;
use Modules\Admin\Http\Requests\UpdateStoreRequest;
use Modules\Admin\Http\Requests\UpdateStoreThemeRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Admin\Http\Requests\UpdateStoreNavbarRequest;

class StoreDesignController extends Controller
{
    use AuthorizesRequests;
    protected $storeService, $store;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
        $this->store = $this->storeService->getStore();
    }

    public function updateNavbar(UpdateStoreNavbarRequest $request)
    {
        $this->authorize('Manage-Store-Navbar');

        $validatedData = $request->validated();
        $updatedStore = $this->store->update($validatedData);

        return $this->responseSuccess('Store navbar updated.', new StoreResource($updatedStore));
    }

    public function deleteNavbar()
    {
        $this->authorize('Manage-Store-Navbar');
        $this->store->update([
            'banner_content' => null,
            'banner_link' => null,
        ]);
        return $this->responseSuccess('Store Navbar Deleted.',$this->store);
    }

    public function updateTheme(UpdateStoreThemeRequest $request)
    {
        $this->authorize('Edit-Store-Design');
        $validatedData = $request->validated();
        $updatedStore = $this->store->update($validatedData);

        return $this->responseSuccess('Store theme updated.', new StoreResource($updatedStore));
    }

    public function updateColors(UpdateStoreRequest $request)
    {
        $this->authorize('Edit-Store-Design');
        $validatedData = $request->validated();
        $this->store->update($validatedData);

        return $this->responseSuccess('Store colors updated.', new StoreResource($this->store));
    }
}
