<?php

namespace App\Services;

use Illuminate\Support\Js;
use Modules\Store\Entities\Product;
use Modules\Customer\Entities\ShoppingCart;

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
            abort(response()->json(['message' => 'error to find this product']));
        }

        return $product;
    }


    public function findProductInCartWithOptions($cart, $product, $optionName, $optionValue)
    {
        $existingProduct = $cart->items()->where('product_id', $product->id)
            ->where('product_option', $optionName)
            ->where('product_option_value', $optionValue)
            ->first();
       
        return $existingProduct;
    }

    public function calculateOptionValueAvailableQuantity($product, $optionName, $optionValue)
    {
        $selectedOption = $product->options()->where('name', $optionName)->first();

        if (!$selectedOption) {
            abort(response()->json(['message' => 'Invalid option selected'], 400));
        }

        $optionValueModel = $selectedOption->values()->where('name', $optionValue)->first();

        if (!$optionValueModel) {
            abort(response()->json(['message' => 'Invalid value selected for the option'], 400));
        }

        return $optionValueModel->quantity;
    }

    public function validateQuantity($availableQuantiy, $totalQuantity)
    {
        if ($totalQuantity > $availableQuantiy) {
            abort(response()->json(['message' => 'Invalid quantity for the featured product'], 400));
        }
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
            'product_option_value' =>         $productOptionValue,
        ]);
    }
}
