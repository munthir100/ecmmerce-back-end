<?php

namespace App\Services;

use Illuminate\Http\Response;
use Modules\Store\Entities\Product;

class ProductService
{
    public function createProductOptions(Product $product, array $optionsData)
    {
        $options = $this->prepareOptions($optionsData);
        $totalValuesQuantity = $this->calculateTotalValuesQuantity($options);
        $this->validateQuantityMatch($product, $totalValuesQuantity);
        $this->createOptions($product, $options);
        $this->createOptionValues($product, $options);
    }

    private function prepareOptions(array $optionsData)
    {
        $options = [];

        foreach ($optionsData as $optionData) {
            $option = [
                'name' => $optionData['name'],
                'values' => [],
            ];

            if (isset($optionData['values']) && is_array($optionData['values'])) {
                foreach ($optionData['values'] as $valueData) {
                    $quantity = isset($valueData['quantity']) ? (int) $valueData['quantity'] : 0;
                    $option['values'][] = [
                        'name' => $valueData['name'],
                        'additional_price' => $valueData['additional_price'] ?? 0,
                        'quantity' => $quantity,
                    ];
                }
            }

            $options[] = $option;
        }

        return $options;
    }

    private function createOptions(Product $product, array $options)
    {
        $product->options()->createMany($options);
    }

    private function calculateTotalValuesQuantity(array $options)
    {
        $totalValuesQuantity = 0;

        foreach ($options as $option) {
            foreach ($option['values'] as $value) {
                $totalValuesQuantity += $value['quantity'];
            }
        }

        return $totalValuesQuantity;
    }

    private function validateQuantityMatch(Product $product, int $totalValuesQuantity)
    {
        if ($product->quantity != $totalValuesQuantity) {
            abort(response()->json([
                'message' => 'invaild quantity',
                'success' => false,
                'statuscode' => Response::HTTP_CONFLICT,
            ]));
        }
    }

    private function createOptionValues(Product $product, array $options)
    {
        foreach ($options as $index => $option) {
            if (!empty($option['values'])) {
                $optionModel = $product->options[$index];
                $optionModel->values()->createMany($option['values']);
            }
        }
    }

    function deleteProduct($product)
    {
        if ($product->orderItems()->exists()) {
            abort(response()->json([
                'message' => 'this product has an orders',
                'success' => false,
                'statuscode' => Response::HTTP_CONFLICT,
            ]));
        }

        $product->delete();
    }
}
