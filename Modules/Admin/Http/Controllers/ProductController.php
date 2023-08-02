<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Arr;
use App\Services\ProductService;
use Illuminate\Routing\Controller;

use Modules\Store\Entities\Product;
use App\Http\Responses\MessageResponse;
use function PHPUnit\Framework\isEmpty;
use App\Exceptions\InvalidQuantityException;
use App\Traits\FindsModelsForAdmin;
use Modules\Admin\Http\Requests\ProductRequest;
use Modules\Admin\Transformers\ProductResource;
use Modules\Admin\Http\Requests\UpdateProductRequest;
use Modules\Admin\Transformers\ProductWithOptionsResource;

class ProductController extends Controller
{
    use FindsModelsForAdmin;
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $term = request()->get('term', '');
        $perPage = request()->get('perPage', 25);
        $adminId = request()->user()->admin->id;
        $products = Product::search($term)->ForAdmin($adminId)->paginate($perPage);

        return new MessageResponse(
            data: ['products' => ProductResource::collection($products)],
            statusCode: 200
        );
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $data['store_id'] = $request->user()->admin->store->id;
        $this->productService->validateSku($data['sku'],$data['store_id']);
        $optionsData = Arr::pull($data, 'options', []);
        try {
            $product = Product::create($data);
            $product->uploadMedia();
            $this->productService->createProductOptions($product, $optionsData);
            return new MessageResponse('Product created successfully', ['product' => new ProductWithOptionsResource($product)]);
        } catch (InvalidQuantityException $e) {
            return new MessageResponse('Invalid quantity', [], 422);
        }
    }


    public function show($productId)
    {
        $product = $this->findModelOrFail(Product::class, $productId);

        return new MessageResponse(
            data: ['product' => new ProductWithOptionsResource($product)],
            statusCode: 200
        );
    }


    public function update(UpdateProductRequest $request, $productId)
    {
        $product = $this->findModelOrFail(Product::class, $productId);

        $data = $request->validated();
        $this->productService->validateSku($data['sku'],$data['store_id']);
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
        $product = $this->findModelOrFail(Product::class, $productId);

        $product->delete();

        return new MessageResponse(
            message: 'Product deleted',
            data: ['product' => new ProductResource($product)],
            statusCode: 200
        );
    }
}

/*
i have a product model :
    public function options()
    {
        return $this->hasMany(ProductOption::class);
    }
and ProductOption :
        protected $fillable = ['name','product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function values()
    {
        return $this->hasMany(ProductOptionValue::class);
    }
and ProductOptionValue:
    protected $fillable = [
        'name',
        'additional_price',
        'quantity',
        'product_option_id',
    ];

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
    what if the customer put the product in cart 
*/












