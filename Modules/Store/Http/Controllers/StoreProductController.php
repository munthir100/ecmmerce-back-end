<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Response;
use Modules\Store\Entities\Store;

use App\Http\Controllers\Controller;
use Modules\Store\Http\Requests\RatingRequest;
use Modules\Admin\Transformers\ProductResource;
use Modules\Admin\Transformers\ProductWithOptionsResource;

class StoreProductController extends Controller
{
    public function products(Store $store)
    {
        $products = $store->products()->useFilters()->dynamicPaginate();

        return $this->responseSuccess(
            data: [
                'products' => ProductResource::collection($products),
                'currency' => $store->default_currency,
            ],
        );
    }
    public function productDetails(Store $store, $productId)
    {
        $product = $this->getProductById($store, $productId);

        return $this->responseSuccess(
            data: [
                new ProductWithOptionsResource($product),
                'currency' => $store->default_currency,
                'rating' => $product->averageRating()
            ],
        );
    }

    public function rateProduct(Store $store, $productId, RatingRequest $request)
    {
        $data = $request->validated();
        $product = $this->getProductById($store, $productId);
        $product->rateOnce($data['rating']);

        return $this->responseSuccess('Thank,s for your feedback');
    }

    public function categorizedProducts(Store $store, $categoryId)
    {
        $products = $store->products()->useFilters()->where('category_id', $categoryId)->dynamicPaginate();

        return $this->responseSuccess(
            data: [
                'products' => ProductResource::collection($products),
                'currency' => $store->default_currency,
            ],
        );
    }

    function featuredProducts(Store $store)
    {
        $products = $store->products()->useFilters()->whereHas('options')->dynamicPaginate();

        return $this->responseSuccess(
            data: [ProductResource::collection($products),],
        );
    }

    private function getProductById($store, $productId)
    {
        $product = $store->products()->find($productId);

        if (!$product) {
            abort(response()->json([
                'message' => 'product not found',
                'success' => false,
                'statuscode' => Response::HTTP_CONFLICT,
            ]));
        }
        
        return $product;
    }
}
