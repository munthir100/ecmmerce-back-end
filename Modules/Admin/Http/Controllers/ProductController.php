<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Arr;
use App\Traits\ModelsForAdmin;
use App\Services\ProductService;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Store\Entities\Product;
use App\Http\Responses\MessageResponse;
use function PHPUnit\Framework\isEmpty;
use App\Exceptions\InvalidQuantityException;
use Essa\APIToolKit\Api\ApiResponse;
use Modules\Admin\Http\Requests\ProductRequest;
use Modules\Admin\Transformers\ProductResource;
use Modules\Admin\Http\Requests\UpdateProductRequest;
use Modules\Admin\Transformers\ProductWithOptionsResource;

class ProductController extends Controller
{
    use ModelsForAdmin, ApiResponse;
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = Product::useFilters()->forAdmin(auth()->user()->admin->id)->dynamicPaginate();

        return $this->responseSuccess('products', ProductResource::collection($products));
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $store = $request->user()->admin->store;
        $request->validateSkuIsUnique($store);
        $optionsData = Arr::pull($data, 'options', []);

        return DB::transaction(function () use ($data, $optionsData, $store) {
            $product = $store->products()->create($data);
            $product->uploadMedia();
            $this->productService->createProductOptions($product, $optionsData);

            return $this->responseCreated('product created', new ProductWithOptionsResource($product));
        });
    }


    public function show($productId)
    {
        $product = $this->findAdminModel(auth()->user()->admin, Product::class, $productId);

        return $this->responseSuccess(data: new ProductWithOptionsResource($product));
    }


    public function update(UpdateProductRequest $request, $productId)
    {
        $admin = auth()->user()->admin;
        $product = $this->findAdminModel($admin, Product::class, $productId);

        $data = $request->validated();
        $request->validateSkuIsUnique($admin->store,$product);



        $optionsData = Arr::pull($data, 'options', []);

        $product->update($data);
        if (isset($data['quantity'])) {
            $product->options()->delete();
            if (!isEmpty($optionsData)) {
                $this->productService->createProductOptions($product, $optionsData);
            }
        }
        if ($request->has('main_image')) {
            $product->clearMediaCollection('main_image');
            $product->addMediaFromRequest('main_image')->toMediaCollection('main_image');
        }
        if ($request->has('sub_images')) {
            $product->clearMediaCollection('sub_images');
            foreach ($request->file('sub_images') as $file) {
                $product->addMedia($file)->toMediaCollection('sub_images');
            }
        }

        return $this->responseSuccess('Product updated', new ProductWithOptionsResource($product));
    }

    public function destroy($productId)
    {
        $product = $this->findAdminModel(auth()->user()->admin, Product::class, $productId);

        $product->delete();

        return $this->responseSuccess('product deleted');
    }
}
