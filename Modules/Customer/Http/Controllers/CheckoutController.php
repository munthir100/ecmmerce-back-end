<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\OrderService;
use Modules\Store\Entities\Store;
use Illuminate\Routing\Controller;
use App\Http\Responses\MessageResponse;
use Illuminate\Contracts\Support\Renderable;

class CheckoutController extends Controller
{
    protected $orderService;
    protected $cartService;

    public function __construct(OrderService $orderService, CartService $cartService)
    {
        $this->orderService = $orderService;
        $this->cartService = $cartService;
    }
    public function checkout(Store $store, Request $request)
    {
        $data = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'captain_id' => 'required|exists:captains,id',
            'payment_type' => 'required|in:bank,cash',
        ]);
        $data['store_id'] = $store->id;
        $customer = $request->user()->customer;
        $cart = $customer->shoppingCart;
        try {
            $order = $this->orderService->createOrderFromCart($customer, $cart, $data);
        } catch (\Exception $e) {
            return new MessageResponse('Failed to create order', [], 500);
        }
        // delete shopping cart
        $cart->products()->detach();
        $cart->delete();
        return new MessageResponse(
            message: 'Checkout completed successfully',
            data: ['order' => $order],
            statusCode: 200
        );
    }
}
