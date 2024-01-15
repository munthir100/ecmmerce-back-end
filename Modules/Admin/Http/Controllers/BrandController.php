<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Store\Entities\Brand;
use App\Http\Controllers\Controller;
use Modules\Admin\Http\Requests\BrandRequest;
use Modules\Admin\Transformers\BrandResource;
use Modules\Admin\Http\Requests\UpdateBrandRequest;

class BrandController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('View-Brand');
        $brands = request()->store->brands()->useFilters()->dynamicPaginate();
        return $brands;
        return $this->responseSuccess(data:[BrandResource::collection($brands)]);
    }

    public function store(BrandRequest $request)
    {
        $this->authorize('Create-Brand');
        $data = $request->validated();
        $brand = request()->store->brands()->create($data);
        $brand->uploadMedia();

        return $this->responseCreated(
            'brand created successfully',
            new BrandResource($brand)
        );
    }

    public function show($brandId)
    {
        $this->authorize('View-Brand');
        $brand = request()->store->brands()->findOrFail($brandId);

        return $this->responseSuccess(
            data: new BrandResource($brand)
        );
    }

    public function update(UpdateBrandRequest $request, $brandId)
    {
        $this->authorize('Edit-Brand');
        $brand = request()->store->brands()->findOrFail($brandId);
        if ($request->has('image')) {
            $brand->clearMediaCollection('image');
            $brand->uploadMedia();
        }
        $brand->update($request->validated());

        return $this->responseSuccess(
            'brand updated',
            new BrandResource($brand),
        );
    }

    public function destroy($brandId)
    {
        $this->authorize('Delete-Brand');
        $brand = request()->store->brands()->findOrFail($brandId);
        $brand->delete();

        return $this->responseSuccess('brand deleted');
    }
}
