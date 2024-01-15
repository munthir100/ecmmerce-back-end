<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\CouponService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\Admin\AdminOrderService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Admin\Transformers\OrderResource;
use Modules\Admin\Http\Requests\ChangeOrderStatus;
use Modules\Admin\Http\Requests\CreateOrderRequest;
use Modules\Admin\Transformers\OrderWithDetailsResource;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('View-Order');
        $orders = request()->store->orders()->useFilters()->with('customer.user', 'captain')->dynamicPaginate();

        return $this->responseSuccess(
            data: [OrderResource::collection($orders)],
        );
    }


    public function store(CreateOrderRequest $request, AdminOrderService $adminOrderService, CouponService $couponService)
    {
        $this->authorize('Create-Order');
        $orderData = $request->validated();
        $productIds = collect($orderData['products'])->pluck('id')->toArray();
        $orderdProducts = $adminOrderService->findProducts(request()->store, $productIds);
        $orderData += $request->validateQuantitiesAndOptions($orderdProducts);
        $totalPrice = $adminOrderService->calculateSelectedProductsTotalPrice($orderdProducts, $orderData);
        $order = $adminOrderService->createOrder(request()->store, $orderData, $totalPrice);
        $orderItems = $adminOrderService->setOrderItems($orderData, $order);
        DB::transaction(function () use ($order, $orderItems) {
            $order->save();
            $order->items()->saveMany($orderItems);
        });
        if ($request->has('coupon')) {
            $coupon = $couponService->findByPromocode(request()->store, $orderData['coupon']);
            $order = $couponService->applyCouponDiscount($order, $coupon, $orderItems, request()->store);
        }

        return $this->responseSuccess('order created', new OrderResource($order));
    }


    public function show($orderId)
    {
        $this->authorize('View-Order-Details');
        $order = request()->store->orders()->findOrFail($orderId)->with([
            'customer.user',
            'captain:id,name,shipping_cost',
            'location:id,name,phone,address_type,lang,lat',
            'items',
            'status',
            'items.product',
        ]);

        return $this->responseSuccess(
            data: ['order' => new OrderWithDetailsResource($order)],
        );
    }


    public function destroy($orderId)
    {
        $this->authorize('Delete-Order');
        $order = request()->store->orders()->findOrFail($orderId);
        $order->delete();

        return $this->responseSuccess('order deleted');
    }

    public function changeStatus($orderId, ChangeOrderStatus $request)
    {
        $this->authorize('Change-Order-Status');
        $data = $request->validated();
        $order = request()->store->orders()->findOrFail($orderId);
        $order->update($data);

        return $this->responseSuccess('status updated', new OrderWithDetailsResource($order));
    }
}
