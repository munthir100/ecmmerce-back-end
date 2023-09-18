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

    public function updateNavbar(UpdateStoreNavbarRequest $request)
    {
        $this->authorize('Manage-Store-Navbar');

        $validatedData = $request->validated();
        $updatedStore = request()->store->update($validatedData);

        return $this->responseSuccess('Store navbar updated.', new StoreResource($updatedStore));
    }

    public function deleteNavbar()
    {
        $this->authorize('Manage-Store-Navbar');
        request()->store->update([
            'banner_content' => null,
            'banner_link' => null,
        ]);
        return $this->responseSuccess('Store Navbar Deleted.',request()->store);
    }

    public function updateTheme(UpdateStoreThemeRequest $request)
    {
        $this->authorize('Edit-Store-Design');
        $validatedData = $request->validated();
        $updatedStore = request()->store->update($validatedData);

        return $this->responseSuccess('Store theme updated.', new StoreResource($updatedStore));
    }

    public function updateColors(UpdateStoreRequest $request)
    {
        $this->authorize('Edit-Store-Design');
        $validatedData = $request->validated();
        request()->store->update($validatedData);

        return $this->responseSuccess('Store colors updated.', new StoreResource(request()->store));
    }
}
