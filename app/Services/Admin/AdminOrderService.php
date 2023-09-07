<?php

namespace App\Services\Admin;

use Modules\Admin\Entities\Status;
use Modules\Customer\Entities\Order;
use Modules\Customer\Entities\OrderItem;

class AdminOrderService
{
    public function calculateSelectedProductsTotalPrice($products, $orderData)
    {
        $totalPrice = 0;
        foreach ($products as $product) {
            $totalPrice += $this->calculateProductPrice($product, $orderData);
        }

        return $totalPrice;
    }

    private function calculateProductPrice($product, $orderData)
    {
        $additionalPrice = 0;

        $requestedQuantity = $this->CalculateRequestedQuantity($product, $orderData);
        // Calculate additional price based on options and values
        $additionalPrice = $this->CalculateAdditionalPrice($product, $orderData);
        // Calculate the total price for the product
        $totalPriceOfProduct = $this->calculateTotalPriceForProduct($product, $requestedQuantity, $additionalPrice);
        return $totalPriceOfProduct;
    }

    private function CalculateRequestedQuantity($product, $orderData)
    {
        $requestedQuantity = 0;
        foreach ($orderData['products'] as $orderedProduct) {
            if ($orderedProduct['id'] == $product->id) {
                // Assuming quantity is stored as a numeric value, convert it to an integer
                if ($product->options->isNotEmpty()) {
                    $requestedQuantity = (int)$orderedProduct['option']['value']['quantity'];
                } else {
                    $requestedQuantity = (int)$orderedProduct['quantity'];
                }
                break;
            }
        }
        return $requestedQuantity;
    }



    private function CalculateAdditionalPrice($product, $orderData)
    {
        $additionalPrice = 0;
        // Calculate additional price based on options and values
        // Iterate through options and values
        if ($product->options->isNotEmpty()) {
            foreach ($product->options as $option) {
                foreach ($option->values as $value) {
                    foreach ($orderData['products'] as $orderedProduct) {
                        if (
                            isset($orderedProduct['option']['name'])
                            && $orderedProduct['option']['name'] == $option->name
                            && isset($orderedProduct['option']['value']['name'])
                            && $orderedProduct['option']['value']['name'] == $value->name
                        ) {
                            // Assuming additional_price is stored as a numeric value, convert it to a float
                            $additionalPrice += (float)$value->additional_price;
                        }
                    }
                }
            }
        }
        return $additionalPrice;
    }

    private function calculateTotalPriceForProduct($product, $quantity, $additionalPrice)
    {
        $price = $product->is_discounted ? $product->price_after_discount : $product->price;
        // Calculate the total price for the product
        return ($price + $additionalPrice) * $quantity;
    }

    public function findProducts($store, array $productIds)
    {
        return $store->products()->whereIn('id', $productIds)->with('options', 'options.values')->get();
    }

    public function createOrder($store, $orderData, $totalPrice)
    {
        $order = new Order([
            'store_id' => $store->id,
            'customer_id' => $orderData['customer_id'],
            'total_price' => $totalPrice,
            'payment_type' => $orderData['payment_type'],
            'captain_id' => $orderData['captain_id'],
            'store_id' => $store->id,
            'location_id' => $orderData['location_id'],
            'status_id' => Status::ORDER_NEW,
        ]);


        return $order;
    }

    public function setOrderItems($orderData)
    {
        $orderItems = array_map(function ($productData) {
            $orderItem = new OrderItem();

            if (isset($productData['option']) && isset($productData['option']['value'])) {
                $orderItem->quantity = $productData['option']['value']['quantity'];
                $orderItem->product_id = $productData['id'];
            } else {
                $orderItem->quantity = $productData['quantity'];
                $orderItem->product_id = $productData['id'];
            }
            

            return $orderItem;
        }, $orderData['products']);

        return $orderItems;
    }
}
