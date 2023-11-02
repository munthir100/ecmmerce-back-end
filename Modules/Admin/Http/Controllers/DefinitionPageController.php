<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Admin\Transformers\DefinitionPageResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Admin\Http\Requests\CreateDefinitionPageRequest;
use Modules\Admin\Http\Requests\UpdateDefinitionPageRequest;

class DefinitionPageController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        // $this->authorize('View-DefinitionPage');
        $definitionPages = request()->store->definitionPages()->useFilters()->dynamicPaginate();

        return $this->responseSuccess(data: DefinitionPageResource::collection($definitionPages));
    }

    public function store(CreateDefinitionPageRequest $request)
    {
        // $this->authorize('Create-DefinitionPage');
        $definitionPage = request()->store->definitionPages()->create($request->validated());

        return $this->responseSuccess('definition page created', new DefinitionPageResource($definitionPage));
    }

    public function show($definitionPageId)
    {
        // $this->authorize('View-DefinitionPage');
        $definitionPage = request()->store->definitionPages()->findOrFail($definitionPageId);

        return $this->responseSuccess(data: new DefinitionPageResource($definitionPage));
    }

    public function update(UpdateDefinitionPageRequest $request, $definitionPageId)
    {
        // $this->authorize('Edit-DefinitionPage');
        $data = $request->validated();
        $definitionPage = request()->store->definitionPages()->findOrFail($definitionPageId);
        $definitionPage->update($data);

        return $this->responseSuccess('definition page updated', new DefinitionPageResource($definitionPage));
    }

    public function destroy($definitionPageId)
    {
        // $this->authorize('Delete-DefinitionPage');
        $definitionPage = request()->store->definitionPages()->findOrFail($definitionPageId);
        $definitionPage->delete();

        return $this->responseSuccess('definition page deleted');
    }

}
