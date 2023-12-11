<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Store\Entities\Product;
use Modules\Customer\Entities\ShoppingCart;

class CartService
{
    public function findProduct($store, $productId)
    {
        $product = $store->products()->findOrFail($productId);

        if ($product->FeaturedProdcut) {
            abort(response()->json([
                'message' => 'this is featured product',
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
        $item = $cart->items()->where('product_id', $product->id)->first();
        if (!$item) {
            abort(response()->json([
                'message' => 'The product does not exist in the cart',
                'success' => false,
                'statuscode' => Response::HTTP_NOT_FOUND,
            ]));
        }
        return $item;
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
                'message' => 'the quantity must be less than or equal ' . $product->quantity . '',
                'success' => false,
                'statuscode' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ]));
        }

        return $totalQuantity;
    }
}
