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
        if (!$product) {
            abort(response()->json(['message' => 'product not found'], 404));
        }
        return $product;
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
            abort(response()->json(['message' => 'invalid quantity'], 404));
        }

        return $totalQuantity;
    }

}
