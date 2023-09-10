<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\StoreService;
use App\Services\CouponService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Customer\Entities\Order;
use App\Services\Admin\AdminOrderService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Admin\Transformers\OrderResource;
use Modules\Admin\Http\Requests\ChangeOrderStatus;
use Modules\Admin\Http\Requests\CreateOrderRequest;
use Modules\Admin\Transformers\OrderWithDetailsResource;

class OrderController extends Controller
{
    use AuthorizesRequests;
    protected $storeService, $store;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
        $this->store = $this->storeService->getStore();
    }

    public function index()
    {
        $this->authorize('View-Order');
        $orders = $this->store->orders()->useFilters()->with('customer.user', 'captain')->dynamicPaginate();

        return $this->responseSuccess(
            data: ['orders' => OrderResource::collection($orders)],
        );
    }


    public function store(CreateOrderRequest $request, AdminOrderService $adminOrderService, CouponService $couponService)
    {
        $this->authorize('Create-Order');
        $orderData = $request->validated();
        $productIds = collect($orderData['products'])->pluck('id')->toArray();
        $orderdProducts = $adminOrderService->findProducts($this->store, $productIds);
        $orderData += $request->validateQuantitiesAndOptions($orderdProducts);
        $totalPrice = $adminOrderService->calculateSelectedProductsTotalPrice($orderdProducts, $orderData);
        $order = $adminOrderService->createOrder($this->store, $orderData, $totalPrice);
        $orderItems = $adminOrderService->setOrderItems($orderData, $order);
        DB::transaction(function () use ($order, $orderItems) {
            $order->save();
            $order->items()->saveMany($orderItems);
        });
        if ($request->has('coupon')) {
            $coupon = $couponService->findByPromocode($this->store, $orderData['coupon']);
            $order = $couponService->applyCouponDiscount($order, $coupon, $orderItems, $this->store);
        }

        return $this->responseSuccess('order created', new OrderResource($order));
    }


    public function show($orderId)
    {
        $this->authorize('View-Order-Details');
        $order = $this->storeService->findStoreModel($this->store, Order::class, $orderId)->with([
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
        $order = $this->storeService->findStoreModel($this->store, Order::class, $orderId);
        $order->delete();

        return $this->responseSuccess('order deleted');
    }

    public function changeStatus($orderId, ChangeOrderStatus $request)
    {
        $this->authorize('Change-Order-Status');
        $data = $request->validated();
        $order = $this->storeService->findStoreModel($this->store, Order::class, $orderId);
        $order->update($data);

        return $this->responseSuccess('status updated', new OrderWithDetailsResource($order));
    }
}
