<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Responses\MessageResponse;
use Modules\Admin\Transformers\StoreResource;
use Modules\Admin\Http\Requests\UpdateColorsRequest;
use Modules\Admin\Http\Requests\UpdateStoreLanguageRequest;
use Modules\Admin\Http\Requests\UpdateStoreStatusRequest;

class StoreAdditionalSettingsController extends Controller
{
    public function updateCommercialRegistration(Request $request)
    {
        $store = $this->getStoreFromRequestUser();

        $data = $request->validate([
            'commercial_registration_no' => 'required|unique:stores,commercial_registration_no,' . $store->id,
        ]);

        $store->update($data);

        return new MessageResponse(
            'Commercial registration number updated',
            new StoreResource($store),
            200
        );
    }

    public function updateStoreLanguage(UpdateStoreLanguageRequest $request)
    {
        $store = $this->getStoreFromRequestUser();

        $data = $request->validated();

        $store->update($data);

        return new MessageResponse(
            'Language updated',
            new StoreResource($store),
            200
        );
    }

    public function updateStatus(UpdateStoreStatusRequest $request)
    {
        $store = $this->getStoreFromRequestUser();

        $data = $request->validated();

        $store->update($data);

        return new MessageResponse(
            'Store status updated successfully.',
            new StoreResource($store),
            200
        );
    }

    public function updateColors(UpdateColorsRequest $request)
    {
        $store = $this->getStoreFromRequestUser();
        $validatedData = $request->validated();

        $store->update($validatedData);

        return new MessageResponse(
            'Store colors updated successfully.',
            new StoreResource($store),
            200
        );
    }



    private function getStoreFromRequestUser()
    {
        return request()->user()->admin->store;
    }
}
