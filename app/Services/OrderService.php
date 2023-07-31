<?php

namespace App\Services;

use App\Services\CartService;
use Modules\Customer\Entities\Order;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Entities\OrderItem;
use Modules\Customer\Entities\ShoppingCart;
use Modules\Customer\Transformers\shoppingCartResource;
use Modules\Shipping\Entities\Captain;
use Modules\Store\Entities\Store;

class OrderService
{
    public function validateShippingMethod($storeCities, $selectedLocation)
    {
        if (!$storeCities->contains('name', $selectedLocation->name)) {
            abort(response()->json('This location does not have a captain'));
        }
    }


    public function SetOrderValues($storeId, Customer $customer, ShoppingCart $ShoppingCart, array $data): Order
    {
        $productsTotalPrice = $this->calculateProductsTotalPrice($ShoppingCart);
        $shippingCost = $this->calculateShippingCost($data['captain_id']);

        $totalPrice = $productsTotalPrice + $shippingCost;
        $order = new Order();
        $order->customer_id = $customer->id;
        $order->total_price = $totalPrice; // Calculate the total price
        $order->payment_type = $data['payment_type']; // Set the payment type
        $order->captain_id = $data['captain_id']; // Set the captain
        $order->store_id = $storeId; // Set the store
        $order->location_id = $data['location_id']; // Set the store
        $order->status = 'new'; // Set the initial status of the order
        $order->save();

        return $order;
    }
    public function calculateProductsTotalPrice($ShoppingCart)
    {
        $totalPrice = $ShoppingCart->products->sum(function ($item) {
            $additionalPrice = $item->pivot->product_option_value ? $item->pivot->additional_price : 0;
            return ($item->price + $additionalPrice) * $item->pivot->quantity;
        });
        $cartResource = new shoppingCartResource($ShoppingCart);
        $cartResource->resource['total_price'] = $totalPrice;

        return $totalPrice;
    }

    public function calculateShippingCost($captainId)
    {
        $captain = Captain::find($captainId);

        return $captain->shipping_cost;
    }













    public function ValidateOrderdItems(Store $store, $ShoppingCart)
    {
        $cartService = new CartService();
        $orderItems = [];



        foreach ($ShoppingCart->items as $cartItem) {
            $product = $cartService->findProduct($store, $cartItem->product_id);
            $validatedQuantity = $cartService->validateQuantityForSingleProduct($product, $cartItem->quantity);

            if (!$validatedQuantity) {
                abort(response()->json('error in validation of quantities'));
            }

            //set order items in array
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $validatedQuantity,
            ];
        }

        return $orderItems;
    }


    public function setOrderItems(Order $order, array $validatedItems)
    {
        $itemsToInsert = [];
        foreach ($validatedItems as $item) {
            $itemsToInsert[] = new OrderItem([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return $order->items()->saveMany($itemsToInsert);
    }
}
