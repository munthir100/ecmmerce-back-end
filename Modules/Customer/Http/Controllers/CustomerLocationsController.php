<?php

namespace Modules\Customer\Http\Controllers;


use Modules\Store\Entities\Store;
use App\Http\Controllers\Controller;
use Modules\Customer\Http\Requests\LocationRequest;
use Modules\Customer\Http\Requests\UpdateLocationRequest;
use Modules\Customer\Transformers\LocationResource;
use Modules\Shipping\Entities\Location;

class CustomerLocationsController extends Controller
{
    public function index(Store $store)
    {
        $customer = request()->user()->customer;
        $locations = $customer->locations()->dynamicPaginate();

        return $this->responseSuccess(data: [LocationResource::collection($locations)]);
    }

    public function store(LocationRequest $request, Store $store)
    {
        $data = $request->validated();
        $data += $request->validateStoreCity($store);
        $customer = $request->user()->customer;
        $location = $customer->locations()->create($data);

        return $this->responseSuccess(
            'Location created successfully',
            new LocationResource($location),
        );
    }

    public function show(Store $store, Location $location)
    {
        $customer = request()->user()->customer;
        if ($location->customer_id !== $customer->id) {
            return $this->responseUnAuthorized('Unauthorized');
        }

        return $this->responseSuccess(
            data: new LocationResource($location),
        );
    }

    public function update(UpdateLocationRequest $request, Store $store, Location $location)
    {
        $data = $request->validated();

        $location->update($data);

        return $this->responseSuccess(
            data: new LocationResource($location),
        );
    }

    public function destroy(Store $store, Location $location)
    {
        $location->delete();

        return $this->responseSuccess(
            'Location deleted successfully',
        );
    }
}
