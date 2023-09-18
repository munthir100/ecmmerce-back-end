<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\StoreService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Modules\Admin\Transformers\StoreResource;
use Modules\Admin\Http\Requests\UpdateStoreRequest;

class StoreSettingsController extends Controller
{
    use AuthorizesRequests;

    function updateBasicInformation(UpdateStoreRequest $request)
    {
        $this->authorize('Manage-Store-Settings');
        $data = $request->validated();
        $data += $request->validateStoreLink(request()->store);
        $updatedStore = request()->store->update($data);

        return $this->responseSuccess('store data updated', new StoreResource($updatedStore));
    }

    function UpdateStoreLogo(UpdateStoreRequest $request)
    {
        $this->authorize('Manage-Store-Settings');
        $request->validated();

        if ($request->has('store_logo')) {
            request()->store->clearMediaCollection('store_logo');
            request()->store->addMediaFromRequest('store_logo')->toMediaCollection('store_logo');
        }

        return $this->responseSuccess('store logo updated');
    }
    function UpdateStoreIcon(UpdateStoreRequest $request)
    {
        $this->authorize('Manage-Store-Settings');
        $request->validated();

        if ($request->has('store_icon')) {
            request()->store->clearMediaCollection('store_icon');
            request()->store->addMediaFromRequest('store_icon')->toMediaCollection('store_icon');
        }

        return $this->responseSuccess('store icon updated');
    }

    function UpdateStoreCity(UpdateStoreRequest $request)
    {
        $this->authorize('Manage-Store-Settings');
        $data = $request->validateStoreCity(request()->store);
        request()->store->update($data);

        return $this->responseSuccess('store city updated', $data);
    }
    
}
