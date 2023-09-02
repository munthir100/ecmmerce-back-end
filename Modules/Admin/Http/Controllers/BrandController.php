<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\StoreService;
use Modules\Store\Entities\Brand;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\BrandRequest;
use Modules\Admin\Transformers\BrandResource;
use Modules\Admin\Http\Requests\UpdateBrandRequest;

class BrandController extends Controller
{
    protected $storeService, $store;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
        $this->store = $this->storeService->getStore();
    }

    public function index()
    {
        $brands = $this->store->brands()->useFilters()->dynamicPaginate();

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
        $brand = $this->storeService->findStoreModel($this->store, Brand::class, $brandId);

        return $this->responseSuccess(
            data: new BrandResource($brand)
        );
    }

    public function update(UpdateBrandRequest $request, $brandId)
    {
        $brand = $this->storeService->findStoreModel($this->store, Brand::class, $brandId);
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
        $brand = $this->storeService->findStoreModel($this->store, Brand::class, $brandId);
        $brand->delete();

        return $this->responseSuccess('brand deleted');
    }
}
