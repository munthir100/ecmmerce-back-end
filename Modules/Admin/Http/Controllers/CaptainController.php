<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\StoreService;
use Illuminate\Routing\Controller;
use Modules\Shipping\Entities\Captain;
use App\Http\Responses\MessageResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Admin\Http\Requests\CaptainRequest;
use Modules\Admin\Transformers\CaptainResource;
use Modules\Admin\Http\Requests\UpdateCaptainRequest;

class CaptainController extends Controller
{
    use AuthorizesRequests;
    protected $storeService,$store;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
        $this->store = $this->storeService->getStore();
    }

    public function index()
    {
        $this->authorize('View-Shipping-Method');
        $captains = $this->store->captains()->useFilters()->dynamicPaginate();

        return $this->responseSuccess(
            data: ['captains' => CaptainResource::collection($captains)],
        );
    }

    public function store(CaptainRequest $request)
    {
        $this->authorize('Create-Shipping-Method');
        $data = $request->validated();
        $data += $request->validateStoreCity($this->store);
        $captain = $this->store->captains()->create($data);
        $captain->cities()->attach($data['city_id']);

        return $this->responseSuccess(
            'Captain created successfully',
            ['captain' => new CaptainResource($captain)],
        );
    }

    public function show($captianId)
    {
        $this->authorize('View-Shipping-Method');
        $captain = $this->storeService->findStoreModel($this->store, Captain::class, $captianId);
        return new MessageResponse(
            data: ['captain' => new CaptainResource($captain)],
            statusCode: 200
        );
    }

    public function update(UpdateCaptainRequest $request, $captianId)
    {
        $this->authorize('Edit-Shipping-Method');
        $data = $request->validated();
        $captain = $this->storeService->findStoreModel($this->store, Captain::class, $captianId);

        $data += $request->validateStoreCity($this->store);
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
        $this->authorize('Delete-Shipping-Method');
        $captain = $this->storeService->findStoreModel($this->store, Captain::class, $captianId);
        $captain->delete();

        return $this->responseSuccess(
            'captain deleted',
            ['captain' => new CaptainResource($captain)],
        );
    }
}
