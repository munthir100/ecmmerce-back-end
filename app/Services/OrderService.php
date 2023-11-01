<?php

namespace App\Services;

use App\Services\CartService;
use Modules\Admin\Entities\Status;
use Modules\Store\Entities\Store;
use Modules\Shipping\Entities\City;
use Modules\Customer\Entities\Order;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Entities\OrderItem;
use Modules\Customer\Transformers\shoppingCartResource;

class OrderService
{
    public function __construct(private CartService $cartService)
    {
    }
    public function getStoreCities(Store $store)
    {
        return City::join('captain_city', 'cities.id', '=', 'captain_city.city_id')
            ->join('captains', 'captain_city.captain_id', '=', 'captains.id')
            ->where('captains.store_id', $store->id)
            ->distinct()
            ->get(['cities.*']);
    }

    public function createOrder(Customer $customer, $productsTotalPrice, $shippingCost, array $data): Order
    {
        $totalPrice = $productsTotalPrice + $shippingCost;

        $order = new Order([
            'customer_id' => $customer->id,
            'total_price' => $totalPrice,
            'payment_type' => $data['payment_type'],
            'captain_id' => $data['captain_id'],
            'store_id' => $data['store_id'],
            'location_id' => $data['location_id'],
            'status_id' => Status::ORDER_NEW,
        ]);
        

        return $order;
    }
    public function validateShippingMethod($storeCities, $selectedLocation)
    {
        if (!$storeCities->contains('id', $selectedLocation->city_id)) {
            abort(response()->json([
                'message' => 'This location does not have a captain',
                'success' => false,
                'statuscode' => 400,
            ]));
        }
    }
    public function calculateProductsTotalPrice($shoppingCart)
    {
        $totalPrice = $shoppingCart->products->sum(fn ($item) => $this->calculateProductPrice($item));

        $cartResource = new ShoppingCartResource($shoppingCart);
        $cartResource->resource['total_price'] = $totalPrice;

        return $totalPrice;
    }


    public function validateOrderedItems(Store $store, $shoppingCart)
    {
        $productIds = $shoppingCart->items->pluck('product_id')->toArray();
        $products = $this->cartService->findProducts($store, $productIds);

        $orderItems = [];

        foreach ($shoppingCart->items as $cartItem) {
            $product = $products->firstWhere('id', $cartItem->product_id);

            if ($product) {
                $validatedQuantity = $this->cartService->validateQuantityForSingleProduct($product, $cartItem->quantity);

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $validatedQuantity,
                ];
            }
        }

        return $orderItems;
    }



    public function setOrderItems(Order $order, array $validatedItems)
    {
        $itemsToInsert = array_map(function ($item) {
            return new OrderItem([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
            ]);
        }, $validatedItems);

        return $itemsToInsert;
    }

    private function calculateProductPrice($item)
    {
        $additionalPrice = $item->pivot->product_option_value ? $item->pivot->additional_price : 0;
        return $item->is_discounted
            ? ($item->price_after_discount + $additionalPrice) * $item->pivot->quantity
            : ($item->price + $additionalPrice) * $item->pivot->quantity;
    }
}
