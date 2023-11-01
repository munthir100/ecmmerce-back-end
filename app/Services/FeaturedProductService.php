<?php

namespace App\Services;

use Modules\Store\Entities\Product;

class FeaturedProductService
{

    public function findProduct($store, $productId, $productOption, $productOptionValue)
    {
        $product = Product::with(['options.values'])
            ->where('store_id', $store->id)
            ->where('id', $productId)
            ->whereHas('options', function ($query) use ($productOption) {
                $query->where('name', $productOption);
            })
            ->whereHas('options.values', function ($query) use ($productOptionValue) {
                $query->where('name', $productOptionValue);
            })->first();


        if (!$product) {
            abort(response()->json([
                'message' => 'product not found',
                'success' => false,
                'statuscode' => 404,
            ]));
        }

        return $product;
    }

    function GetProductOptionValue($product, $requestedOptionValue)
    {
        $OptiondValue = $product['options']->flatMap(function ($option) use ($requestedOptionValue) {
            return $option->values->where('name', $requestedOptionValue);
        })->first();


        return $OptiondValue;
    }
    public function findProductInCartWithOptions($cart, $product, $optionName, $optionValue)
    {
        $existingProduct = $cart->items()->where('product_id', $product->id)
            ->where('product_option', $optionName)
            ->where('product_option_value', $optionValue->name)
            ->first();

        return $existingProduct;
    }

    public function calculateOptionValueAvailableQuantity($product, $optionName, $optionValue)
    {
        $selectedOption = $product->options()->where('name', $optionName)->first();

        $optionValueModel = $selectedOption->values()->where('name', $optionValue)->first();

        return $optionValueModel->quantity;
    }

    public function validateQuantity($availableQuantiy, $totalQuantity)
    {
        if ($totalQuantity > $availableQuantiy) {
            abort(response()->json([
                'message' => 'invaild quantity not found',
                'success' => false,
                'statuscode' => 400,
            ]));
        }
    }

    public function calculateTotalQuantity($existingProduct, $requestedQuantity)
    {
        if ($existingProduct) {
            return $existingProduct->quantity + $requestedQuantity;
        }
        return $requestedQuantity;
    }

    public function calculateTotalPrice($product, $productOptionValue, $requestedQuantity)
    {
        $totalPrice = $product->price + ($productOptionValue->additional_price * $requestedQuantity);

        return $totalPrice;
    }


    public function addItemToCart(
        $store,
        $cart,
        $productId,
        $productOption,
        $productOptionValue,
        $existingProduct,
        $totalQuantity
    ) {
        if ($existingProduct) {
            return $existingProduct->update([
                'quantity' => $totalQuantity
            ]);
        }


        return $cart->items()->create([
            'quantity'  => $totalQuantity,
            'store_id' =>         $store->id,
            'product_id' =>         $productId,
            'product_option' =>         $productOption,
            'product_option_value' =>         $productOptionValue->name,
            'additional_price' =>         $productOptionValue->additional_price,
        ]);
    }
}
