<?php

namespace Modules\Customer\Http\Controllers;

use App\Services\OrderService;
use Modules\Store\Entities\Store;
use Illuminate\Routing\Controller;
use App\Http\Responses\MessageResponse;
use App\Services\CouponService;
use App\Services\CustomerService;
use Modules\Customer\Entities\ShoppingCart;
use Modules\Customer\Transformers\OrderResource;
use Modules\Customer\Http\Requests\CheckOutRequest;
use Modules\Shipping\Entities\Captain;
use Modules\Shipping\Entities\Location;

class CheckoutController extends Controller
{
    public function checkout(
        Store $store,
        CheckOutRequest $request,
        OrderService $orderService,
        CustomerService $customerService,
        CouponService $couponService
    ) {
        $data = $request->validated();
        $data['store_id'] = $store->id;
        $customer = $request->user()->customer;
        $captain = Captain::findModelById($store, Captain::class, $data['captain_id']);
        $storeCities = $orderService->getStoreCities($store);
        $selectedLocation = $customerService->findModelById($customer, Location::class, $data['location_id']);

        $orderService->validateShippingMethod($storeCities, $selectedLocation);
        $shoppingCart = $customerService->findModel($customer, ShoppingCart::class);
        $validatedItems = $orderService->validateOrderedItems($store, $shoppingCart);
        $productsTotalPrice = $orderService->calculateProductsTotalPrice($shoppingCart);

        $order = $orderService->createOrder(
            $customer,
            $productsTotalPrice,
            $captain->shipping_cost,
            $data
        );
        $orderItems = $orderService->setOrderItems($order, $validatedItems);
        if ($request->has('coupon')) {
            $coupon = $couponService->findByPromocode($store, $data['coupon']);
            $order = $couponService->applyCouponDiscount($order, $coupon, $orderItems, $store);
        }

        $order->save();
        $order->items()->saveMany($orderItems);
        $shoppingCart->delete();


        return new MessageResponse('order created', [
            'order' => new OrderResource($order),
            'items' => $orderItems,
        ], statusCode: 200);
    }
}
