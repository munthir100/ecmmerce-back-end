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


    public function updateCommercialRegistration(UpdateStoreRequest $request)
    {
        $this->authorize('Manage-Store-Settings');
        $data = $request->validateCommercialRegistration(request()->store);
        request()->store->update($data);

        return $this->responseSuccess('Commercial registration number updated', new StoreResource(request()->store));
    }

    public function updateStoreLanguage(UpdateStoreRequest $request)
    {
        $this->authorize('Manage-Store-Settings');
        $data = $request->validated();
        request()->store->update($data);

        return $this->responseSuccess('Language updated', new StoreResource(request()->store));
    }

    public function updateStatus(UpdateStoreRequest $request)
    {
        $this->authorize('Manage-Store-Settings');
        $data = $request->validated();
        request()->store->update($data);

        return $this->responseSuccess('Store status updated.', new StoreResource(request()->store));
    }

}
