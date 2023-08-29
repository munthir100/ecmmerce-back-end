<?php

namespace Modules\Admin\Http\Controllers;

use App\Traits\ModelsForAdmin;
use Modules\Store\Entities\Brand;
use Illuminate\Routing\Controller;
use Essa\APIToolKit\Api\ApiResponse;
use Modules\Admin\Http\Requests\BrandRequest;
use Modules\Admin\Transformers\BrandResource;
use Modules\Admin\Http\Requests\UpdateBrandRequest;

class BrandController extends Controller
{
    use ModelsForAdmin, ApiResponse;

    public function index()
    {
        $brands = Brand::useFilters()->forAdmin(auth()->user()->admin->id)->dynamicPaginate();

        return $this->responseSuccess(
            data: ['brands' => BrandResource::collection($brands)],
        );
    }

    public function store(BrandRequest $request)
    {
        $data = $request->validated();
        $brand = Brand::create($data);
        $brand->uploadMedia();

        return $this->responseCreated(
            'brand created successfully',
            new BrandResource($brand)
        );
    }

    public function show($brandId)
    {
        $brand = $this->findAdminModel(auth()->user()->admin, Brand::class, $brandId);

        return $this->responseSuccess(
            data: new BrandResource($brand)
        );
    }

    public function update(UpdateBrandRequest $request, $brandId)
    {
        $brand = $this->findAdminModel(auth()->user()->admin, Brand::class, $brandId);
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
        $brand = $this->findAdminModel(auth()->user()->admin, Brand::class, $brandId);
        $brand->delete();

        return $this->responseSuccess('brand deleted');
    }

}
