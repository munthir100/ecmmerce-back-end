<?php

namespace Modules\Admin\Http\Controllers\Settings;

use App\Http\Responses\MessageResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Transformers\StoreResource;

class AdminStoreController extends Controller
{
    function UpdateStoreData(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|string|max:255|unique:stores',
            'description' => 'required|string|max:255',
        ]);
        $store = request()->user()->admin->store;
        $store->update($data);

        return new MessageResponse('store data updated', new StoreResource($store), 200);
    }

    function UpdateStoreLogo(Request $request)
    {
        $request->validate(['store_logo' => 'image']);
        $store = request()->user()->admin->store;

        if ($request->has('store_logo')) {
            $store->clearMediaCollection('store_logo');
            $store->addMediaFromRequest('store_logo')->toMediaCollection('store_logo');
        }
    }
    function UpdateStoreIcon(Request $request)
    {
        $request->validate(['store_icon' => 'image']);
        $store = request()->user()->admin->store;

        if ($request->has('store_icon')) {
            $store->clearMediaCollection('store_icon');
            $store->addMediaFromRequest('store_icon')->toMediaCollection('store_icon');
        }
    }

    function UpdateStoreCity(Request $request)
    {
        $data = $request->validate([
            'city_id' => 'required|exists:cities,id'
        ]);
        $store = request()->user()->admin->store;
        $store->update($data);

        return new MessageResponse('store data updated', new StoreResource($store), 200);
    }
}
