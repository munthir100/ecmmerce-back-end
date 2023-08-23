<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Responses\MessageResponse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Admin\Transformers\StoreResource;
use Modules\Admin\Http\Requests\UpdateStoreNavbarRequest;
use Modules\Admin\Http\Requests\UpdateStoreThemeRequest;

class StoreDesignController extends Controller
{
    public function updateNavbar(UpdateStoreNavbarRequest $request)
    {
        $store = $this->getStoreFromRequestUser();
        $validatedData = $request->validated();

        $store->update($validatedData);

        return new MessageResponse(
            'Store navbar updated successfully.',
            new StoreResource($store),
            200
        );
    }

    public function updateTheme(UpdateStoreThemeRequest $request)
    {
        $store = $this->getStoreFromRequestUser();
        $validatedData = $request->validated();

        $store->update($validatedData);

        return new MessageResponse(
            'Store theme updated successfully.',
            new StoreResource($store),
            200
        );
    }

    private function getStoreFromRequestUser()
    {
        return request()->user()->admin->store;
    }
}
