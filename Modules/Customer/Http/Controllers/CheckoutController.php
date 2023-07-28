<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\OrderService;
use Modules\Store\Entities\Store;
use Illuminate\Routing\Controller;
use Modules\Shipping\Entities\City;
use App\Http\Responses\MessageResponse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Customer\Transformers\OrderResource;
use Modules\Customer\Http\Requests\CheckOutRequest;

class CheckoutController extends Controller
{
    protected $orderService;
    protected $cartService;

    public function __construct(OrderService $orderService, CartService $cartService)
    {
        $this->orderService = $orderService;
        $this->cartService = $cartService;
    }
    public function checkout(Store $store, CheckOutRequest $request)
    {
        $data = $request->validated();
        $customer = $request->user()->customer;
        $ShoppingCart = $customer->shoppingCart;
        // Assuming $store is an instance of the Store model
        $storeCities = City::join('captain_city', 'cities.id', '=', 'captain_city.city_id')
        ->join('captains', 'captain_city.captain_id', '=', 'captains.id')
        ->where('captains.store_id', $store->id)
        ->distinct()
        ->get(['cities.*']);
    


        $selectedLocation = $customer->locations()->where('id', $data['location_id'])->first();

        $this->orderService->validateShippingMethod($storeCities, $selectedLocation);

        $validatedItems = $this->orderService->ValidateOrderdItems($store, $ShoppingCart);

        $order = $this->orderService->SetOrderValues($store->id,$customer, $ShoppingCart, $data);
        $orderItems = $this->orderService->setOrderItems($order, $validatedItems);
        $ShoppingCart->delete();
        
        return new MessageResponse('order created', [
            'order' => new OrderResource($order),
            'items' => $orderItems,
        ], statusCode: 200);
    }
}
