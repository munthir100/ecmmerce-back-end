<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Store\Entities\Brand;
use Illuminate\Routing\Controller;
use App\Traits\ModelsForAdmin;
use App\Http\Responses\MessageResponse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Admin\Http\Requests\BrandRequest;
use Modules\Admin\Transformers\BrandResource;
use Modules\Admin\Http\Requests\UpdateBrandRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BrandController extends Controller
{
    use ModelsForAdmin;

    public function index()
    {
        $term = request()->get('term', '');
        $perPage = request()->get('perPage', 25);
        $brands = $this->getAdminModels(Brand::class, $term, $perPage);

        return new MessageResponse(
            data: ['brands' => BrandResource::collection($brands)],
        );
    }

    public function store(BrandRequest $request)
    {
        $data = $request->validated();
        $brand = Brand::create($data);
        $brand->uploadMedia();

        return new MessageResponse(
            message: 'brand created successfully',
            data: ['brand' => new BrandResource($brand)]
        );
    }

    public function show($brandId)
    {
        $brand = $this->findAdminModel(Brand::class, $brandId);

        return new MessageResponse(
            data: ['brand' => new BrandResource($brand)]
        );
    }

    public function update(UpdateBrandRequest $request, $brandId)
    {
        $brand = $this->findAdminModel(Brand::class, $brandId);
        if ($request->has('image')) {
            $brand->clearMediaCollection('image');
            $brand->uploadMedia();
        }
        $brand->update($request->validated());

        return new MessageResponse(
            message: 'brand updated',
            data: ['brand' => new BrandResource($brand)],
        );
    }

    public function destroy($brandId)
    {
        $brand = $this->findAdminModel(Brand::class, $brandId);
        $brand->delete();

        return new MessageResponse(
            message: 'brand deleted',
            data: ['brand' => new BrandResource($brand)],
        );
    }

    private function findBrandOrFail($brandId)
    {
        try {
            return Brand::forAdmin(auth()->id())->findOrFail($brandId);
        } catch (ModelNotFoundException $e) {
            abort(response()->json(['message' => 'Brand not found'], 404));
        }
    }
}
