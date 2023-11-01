<?php

namespace App\Services;

use Illuminate\Http\Request;
use Modules\Store\Entities\Product;
use Modules\Customer\Entities\ShoppingCart;

class CartService
{
    public function findProduct($store, $productId)
    {
        $product = $store->products->find($productId);
   
        if($product->FeaturedProdcut){
            abort(response()->json([
                'message' => 'this is featured product',
                'success' => false,
                'statuscode' => 404,
            ]));
        }
        if (!$product) {
            abort(response()->json([
                'message' => 'product not found',
                'success' => false,
                'statuscode' => 404,
            ]));
        }
        return $product;
    }

    public function findProducts($store, array $productIds)
    {
        $products = $store->products->whereIn('id', $productIds);

        if ($products->count() !== count($productIds)) {
            $missingProductIds = array_diff($productIds, $products->pluck('id')->toArray());
            abort(response()->json([
                'message' => 'some products not found',
                'success' => false,
                'statuscode' => 404,
            ]));
        }

        return $products;
    }


    public function validateProductQuantity(Product $product, $quantity, ShoppingCart $cart)
    {
        $existingProduct = $this->CheckIfProductExistsInCart($product, $cart);
        $totalQuantity = $this->calculateTotalQuantity($existingProduct, $quantity);
        $validatedQuantity = $this->validateQuantityForSingleProduct($product, $totalQuantity);

        return $validatedQuantity;
    }

    public function CheckIfProductExistsInCart(Product $product, ShoppingCart $cart)
    {
        return $cart->items()->where('product_id', $product->id)->first();
    }
    public function calculateTotalQuantity($existingProduct, $requestedQuantity)
    {
        if ($existingProduct) {
            return $existingProduct->quantity + $requestedQuantity;
        }

        return $requestedQuantity;
    }

    public function validateQuantityForSingleProduct(Product $product, $totalQuantity)
    {
        if (!$product->unspecified_quantity && $totalQuantity > $product->quantity) {
            abort(response()->json([
                'message' => 'invaild quantity',
                'success' => false,
                'statuscode' => 400,
            ]));
        }

        return $totalQuantity;
    }
}
