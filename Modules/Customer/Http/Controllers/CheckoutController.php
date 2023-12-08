<?php

namespace Modules\Customer\Http\Controllers;

use App\Services\OrderService;
use App\Services\CouponService;
use App\Services\CustomerService;
use Modules\Store\Entities\Store;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Shipping\Entities\Captain;
use Modules\Shipping\Entities\Location;
use Modules\Customer\Entities\ShoppingCart;
use Modules\Customer\Transformers\OrderResource;
use Modules\Customer\Http\Requests\CheckOutRequest;

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
        $captain = Captain::findStoreModel($store, Captain::class, $data['captain_id']);
        $storeCities = $orderService->getStoreCities($store);
        $selectedLocation = $customerService->findCustomerModel($customer, Location::class, $data['location_id']);

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
        DB::transaction(function () use ($order, $orderItems,$shoppingCart) {
            $order->save();
            $order->items()->saveMany($orderItems);
            $shoppingCart->items()->delete();
        });
        


        return $this->responseSuccess('order created', [
            'order' => new OrderResource($order),
            'items' => $orderItems,
        ]);
    }
}
