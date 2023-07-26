<?php

namespace App\Services;

use Modules\Store\Entities\Product;
use Modules\Customer\Entities\Order;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Entities\OrderDetail;
use Modules\Customer\Entities\ShoppingCart;

class OrderService
{
    public function createOrderFromCart(Customer $customer, ShoppingCart $cart, array $data): Order
    {
        // Create a new order instance
        $order = new Order();
        $order->customer_id = $customer->id;
        $order->status = 'new'; // Set the initial status of the order
        $order->total_price = $cart->TotalPrice; // Calculate the total price
        $order->payment_type = $data['payment_type']; // Set the payment type
        $order->store_id = $data['store_id']; // Set the store
        $order->captain_id = $data['captain_id']; // Set the captain

        // Save the order
        $order->save();

        // Move cart items to order details
        foreach ($cart->products as $cartProduct) {
            $product = Product::find($cartProduct->id);

            // Create a new order detail for each product in the cart
            $orderDetail = new OrderDetail();
            $orderDetail->order_id = $order->id;
            $orderDetail->product_id = $product->id;
            $orderDetail->quantity = $cartProduct->pivot->quantity;

            // Save the order detail
            $orderDetail->save();
        }

        return $order;
    }
}
