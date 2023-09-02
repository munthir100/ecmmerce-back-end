<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StoreService;
use Illuminate\Routing\Controller;
use Modules\Customer\Entities\Order;
use Modules\Admin\Transformers\OrderResource;
use Modules\Admin\Http\Requests\ChangeOrderStatus;
use Modules\Admin\Http\Requests\CreateOrderRequest;
use Modules\Admin\Transformers\OrderWithDetailsResource;

class OrderController extends Controller
{
    protected $storeService, $store;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
        $this->store = $this->storeService->getStore();
    }

    public function index()
    {
        $orders = $this->store->orders()->useFilters()->with('customer.user', 'captain')->dynamicPaginate();

        return $this->responseSuccess(
            data: ['orders' => OrderResource::collection($orders)],
        );
    }

    public function store(CreateOrderRequest $request)
    {
        //
    }

    public function show($orderId)
    {
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
        $order = $this->storeService->findStoreModel($this->store, Order::class, $orderId);
        $order->delete();

        return $this->responseSuccess('order deleted');
    }

    public function changeStatus($orderId, ChangeOrderStatus $request)
    {
        $data = $request->validated();
        $order = $this->storeService->findStoreModel($this->store, Order::class, $orderId);
        $order->update($data);

        return $this->responseSuccess('status updated', new OrderWithDetailsResource($order));
    }
}
