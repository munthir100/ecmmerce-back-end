<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Arr;
use App\Services\ProductService;
use App\Services\StoreService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Store\Entities\Product;
use function PHPUnit\Framework\isEmpty;
use Modules\Admin\Http\Requests\ProductRequest;
use Modules\Admin\Transformers\ProductResource;
use Modules\Admin\Http\Requests\UpdateProductRequest;
use Modules\Admin\Transformers\ProductWithOptionsResource;

class ProductController extends Controller
{
    use AuthorizesRequests;
    protected $productService, $storeService,$store;

    public function __construct(ProductService $productService, StoreService $storeService)
    {
        $this->productService = $productService;
        $this->storeService = $storeService;
        $this->store = $this->storeService->getStore();
    }

    public function index()
    {
        $products = $this->store->products()->useFilters()->dynamicPaginate();

        return $this->responseSuccess('products', ProductResource::collection($products));
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $request->validateSkuIsUnique($this->store);
        $optionsData = Arr::pull($data, 'options', []);

        return DB::transaction(function () use ($data, $optionsData) {
            $product = $this->store->products()->create($data);
            $product->uploadMedia();
            $this->productService->createProductOptions($product, $optionsData);

            return $this->responseCreated('product created', new ProductWithOptionsResource($product));
        });
    }


    public function show($productId)
    {
        $product = $this->storeService->findStoreModel($this->store, Product::class, $productId);

        return $this->responseSuccess(data: new ProductWithOptionsResource($product));
    }


    public function update(UpdateProductRequest $request, $productId)
    {
        $product = $this->storeService->findStoreModel($this->store, Product::class, $productId);
        $data = $request->validated();
        $request->validateSkuIsUnique($this->store, $product);



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
        $product = $this->storeService->findStoreModel($this->store, Product::class, $productId);
        $this->productService->deleteProduct($product);

        return $this->responseSuccess('product deleted');
    }
}
