<?php

namespace App\Services;

use Modules\Store\Entities\Product;
use App\Exceptions\InvalidQuantityException;

class ProductService
{
    public function createProductOptions(Product $product, array $optionsData)
    {
        $options = [];
        $totalValuesQuantity = 0;

        foreach ($optionsData as $optionData) {
            $options[] = [
                'name' => $optionData['name'],
            ];

            if (isset($optionData['values']) && is_array($optionData['values'])) {
                $values = [];

                foreach ($optionData['values'] as $valueData) {
                    $value = [
                        'name' => $valueData['name'],
                        'additional_price' => $valueData['additional_price'] ?? 0,
                        'quantity' => $valueData['quantity'] ?? null,
                    ];

                    $quantity = isset($valueData['quantity']) ? (int) $valueData['quantity'] : 0;
                    $totalValuesQuantity += $quantity;

                    $values[] = $value;
                }

                $options[count($options) - 1]['values'] = $values;
            }
        }

        $product->options()->createMany($options);

        if (!$product->unspecified_quantity) {
            $this->checkQuantity($product, $totalValuesQuantity);
        }
        
        foreach ($optionsData as $index => $optionData) {
            if (isset($optionData['values']) && is_array($optionData['values'])) {
                $option = $product->options[$index];
                $values = $options[$index]['values'];

                $option->values()->createMany($values);
            }
        }
    }

    private function checkQuantity(Product $product, int $totalValuesQuantity)
    {
        $productQuantity = isset($product->quantity) ? (int) $product->quantity : 0;
        if ($totalValuesQuantity > $productQuantity) {
            throw new InvalidQuantityException();
        }
    }
}
