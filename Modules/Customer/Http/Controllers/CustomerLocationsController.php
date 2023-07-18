<?php

namespace Modules\Customer\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Store\Entities\Store;
use App\Http\Controllers\Controller;
use App\Http\Responses\MessageResponse;
use Modules\Customer\Http\Requests\LocationRequest;
use Modules\Customer\Http\Requests\UpdateLocationRequest;
use Modules\Customer\Transformers\LocationResource;
use Modules\Shipping\Entities\Location;

class CustomerLocationsController extends Controller
{
    public function index(Store $store)
    {
        $customer = auth()->user()->customer;
        $locations = $customer->locations;

        return new MessageResponse(data: LocationResource::collection($locations));
    }

    public function store(LocationRequest $request, Store $store)
    {
        $data = $request->validated();

        $customer = $request->user()->customer;
        $location = $customer->locations()->create($data);

        return new MessageResponse(
            message: 'Location created successfully',
            data: new LocationResource($location),
            statusCode: 200
        );
    }

    public function show(Store $store, Location $location)
    {
        $customer = auth()->user()->customer;
        if ($location->customer_id !== $customer->id) {
            return response()->json('Unauthorized', 401);
        }

        return new MessageResponse(
            data: new LocationResource($location),
            statusCode: 200
        );
    }

    public function update(UpdateLocationRequest $request, Store $store, Location $location)
    {
        $data = $request->validated();

        $location->update($data);

        return new MessageResponse(
            data: new LocationResource($location),
            statusCode: 200
        );
    }

    public function destroy(Store $store, Location $location)
    {
        $location->delete();

        return new MessageResponse(
            message: 'Location deleted successfully',
            statusCode: 200
        );
    }
}
