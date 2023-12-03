<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\StoreService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Controller;
use Modules\Admin\Transformers\StoreResource;
use Modules\Admin\Http\Requests\UpdateStoreRequest;

class StoreSettingsController extends Controller
{
    use AuthorizesRequests;

    function getStoreInformation()
    {
        return $this->responseSuccess(data: new StoreResource(request()->store));
    }
    
    function updateStoreInformation(UpdateStoreRequest $request)
    {
        $this->authorize('Manage-Store-Settings');
        $data = $request->validated();
        request()->store->update($data);

        return $this->responseSuccess('store data updated', new StoreResource(request()->store));
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
}
