<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Shipping\Entities\Captain;
use App\Http\Responses\MessageResponse;
use App\Traits\ModelsForAdmin;
use Illuminate\Contracts\Support\Renderable;
use Modules\Admin\Http\Requests\CaptainRequest;
use Modules\Admin\Http\Requests\UpdateCaptainRequest;
use Modules\Admin\Transformers\CaptainResource;

class CaptainController extends Controller
{
    use ModelsForAdmin;
    public function index()
    {
        $term = request()->get('term', '');
        $perPage = request()->get('perPage', 25);
        $storeId = request()->user()->admin->store->id;
        $captains = Captain::search($term)->with('cities')->where('store_id', $storeId)->paginate($perPage);

        return new MessageResponse(
            data: ['captains' => CaptainResource::collection($captains)],
            statusCode: 200
        );
    }

    public function store(CaptainRequest $request)
    {
        $data = $request->validated();
        $storeID = $request->user()->admin->store->id;
        $data['store_id'] = $storeID;
        $captain = Captain::create($data);
        $captain->cities()->attach($data['city_id']);

        return new MessageResponse(
            message: 'Captain created successfully',
            data: ['captain' => new CaptainResource($captain)],
            statusCode: 200
        );
    }

    public function show($captianId)
    {
        $captain = $this->findAdminModel(Captain::class, $captianId);
        return new MessageResponse(
            data: ['captain' => new CaptainResource($captain)],
            statusCode: 200
        );
    }

    public function update(UpdateCaptainRequest $request, $captianId)
    {
        $captain = $this->findAdminModel(Captain::class, $captianId);
        $data = $request->validated();
        $captain->update($data);
        if ($request->has('city_id')) {
            $captain->cities()->sync($data['city_id']);
        }
        return new MessageResponse(
            message: 'captain data updated',
            data: ['captain' => new CaptainResource($captain)],
            statusCode: 200
        );
    }

    public function destroy($captianId)
    {
        $captain = $this->findAdminModel(Captain::class, $captianId);
        $captain->delete();

        return new MessageResponse(
            message: 'captain deleted',
            data: ['captain' => new CaptainResource($captain)],
            statusCode: 200
        );
    }
}
