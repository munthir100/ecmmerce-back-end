<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ModelsForAdmin;
use Illuminate\Routing\Controller;
use Essa\APIToolKit\Api\ApiResponse;
use Modules\Shipping\Entities\Captain;
use App\Http\Responses\MessageResponse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Admin\Http\Requests\CaptainRequest;
use Modules\Admin\Transformers\CaptainResource;
use Modules\Admin\Http\Requests\UpdateCaptainRequest;

class CaptainController extends Controller
{
    use ModelsForAdmin, ApiResponse;

    public function index()
    {
        $captains = auth()->user()->admin->store->captains()->useFilters()->with('cities')->dynamicPaginate();

        return $this->responseSuccess(
            data: ['captains' => CaptainResource::collection($captains)],
        );
    }

    public function store(CaptainRequest $request)
    {
        $data = $request->validated();
        $store = $request->user()->admin->store;
        $data += $request->validateStoreCity($store);
        $captain = $store->captains()->create($data);
        $captain->cities()->attach($data['city_id']);

        return $this->responseSuccess(
            'Captain created successfully',
            ['captain' => new CaptainResource($captain)],
        );
    }

    public function show($captianId)
    {
        $captain = $this->findAdminModel(auth()->user()->admin, Captain::class, $captianId);
        return new MessageResponse(
            data: ['captain' => new CaptainResource($captain)],
            statusCode: 200
        );
    }

    public function update(UpdateCaptainRequest $request, $captianId)
    {
        $data = $request->validated();
        $admin = auth()->user()->admin;
        $captain = $this->findAdminModel($admin, Captain::class, $captianId);
        $data += $request->validateStoreCity($admin->store);
        $captain->update($data);
        
        if ($request->has('city_id')) {
            $captain->cities()->detach();
            $captain->cities()->sync($data['city_id']);
        }
        return $this->responseSuccess(
            'captain data updated',
            ['captain' => new CaptainResource($captain)],
        );
    }

    public function destroy($captianId)
    {
        $captain = $this->findAdminModel(auth()->user()->admin, Captain::class, $captianId);
        $captain->delete();

        return $this->responseSuccess(
            'captain deleted',
            ['captain' => new CaptainResource($captain)],
        );
    }
}
