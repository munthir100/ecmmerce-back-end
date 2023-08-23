<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use App\Http\Responses\MessageResponse;
use Modules\Admin\Transformers\StoreResource;
use Modules\Admin\Http\Requests\UpdateStoreLogoRequest;
use Modules\Shipping\Entities\City;

class StoreSettingsController extends Controller
{
    function updateBasicInformation(Request $request)
    {
        $store = $this->getStoreFromRequestUser();

        $data = $request->validate([
            'name' => 'required|string',
            'link' => "required|string|unique:stores,link,{$store->id}",
        ]);

        $store->update($data);

        return new MessageResponse(
            'store data updated',
            new StoreResource($store),
            200
        );
    }

    function updateStoreLogo(Request $request)
    {
        $request->validate(['store_logo' => 'image|required']);
        $store = $this->getStoreFromRequestUser();

        $store->clearMediaCollection();
        $store->uploadMedia('store_logo');

        return new MessageResponse('logo updated', new StoreResource($store), 200);
    }

    function UpdateStoreIcon(Request $request)
    {
        $request->validate(['store_icon' => 'image|required']);
        $store = $this->getStoreFromRequestUser();

        $store->clearMediaCollection();
        $store->uploadMedia('store_icon');

        return new MessageResponse('icon updated', new StoreResource($store), 200);
    }

    function UpdateStoreCity(Request $request)
    {
        $store = $this->getStoreFromRequestUser();
        $data = $request->validate([
            'city_id' => [
                'required',
                Rule::exists('cities', 'id')->whereIn('country_id', $store->countries->pluck('id'))
            ]
        ]);
        $store->update($data);

        return new MessageResponse('store city updated', ['city' => $store->city], 200);
    }

    private function getStoreFromRequestUser()
    {
        return request()->user()->admin->store;
    }
}
