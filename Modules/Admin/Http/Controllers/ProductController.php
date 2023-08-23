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
use Modules\Admin\Http\Requests\ProductRequest;
use Modules\Admin\Transformers\ProductResource;
use Modules\Admin\Http\Requests\UpdateProductRequest;
use Modules\Admin\Transformers\ProductWithOptionsResource;

class ProductController extends Controller
{
    use ModelsForAdmin;
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $term = request()->get('term', '');
        $perPage = request()->get('perPage', 25);
        $products = $this->getAdminModels(Product::class, $term, $perPage);

        return new MessageResponse(
            data: ['products' => ProductResource::collection($products)],
            statusCode: 200
        );
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $data['store_id'] = $request->user()->admin->store->id;
        $this->productService->validateSku($data['sku'], $data['store_id']);
        $optionsData = Arr::pull($data, 'options', []);

        return DB::transaction(function () use ($data, $optionsData) {
            $product = Product::create($data);
            $product->uploadMedia();
            $this->productService->createProductOptions($product, $optionsData);
            return new MessageResponse('Product created successfully', ['product' => new ProductWithOptionsResource($product)]);
        });
    }


    public function show($productId)
    {
        $product = $this->findAdminModel(Product::class, $productId);

        return new MessageResponse(
            data: ['product' => new ProductWithOptionsResource($product)],
            statusCode: 200
        );
    }


    public function update(UpdateProductRequest $request, $productId)
    {
        $product = $this->findAdminModel(Product::class, $productId);

        $data = $request->validated();
        $this->productService->validateSku($data['sku'], $data['store_id']);
        $optionsData = Arr::pull($data, 'options', []);
        try {
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
            return new MessageResponse(
                message: 'Product updated',
                data: ['product' => new ProductWithOptionsResource($product)],
                statusCode: 200
            );
        } catch (InvalidQuantityException $e) {
            return new MessageResponse('Invalid quantity', [], 422);
        }
    }

    public function destroy($productId)
    {
        $product = $this->findAdminModel(Product::class, $productId);

        $product->delete();

        return new MessageResponse(
            message: 'Product deleted',
            data: ['product' => new ProductResource($product)],
            statusCode: 200
        );
    }
}
