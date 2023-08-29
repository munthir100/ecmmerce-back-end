<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Customer\Entities\Order;
use App\Http\Responses\MessageResponse;
use App\Traits\ModelsForAdmin;
use Essa\APIToolKit\Api\ApiResponse;
use Modules\Admin\Transformers\OrderResource;
use Modules\Admin\Transformers\OrderWithDetailsResource;

class OrderController extends Controller
{
    use ModelsForAdmin, ApiResponse;

    public function index()
    {
        $orders = Order::useFilters()->ForAdmin(auth()->user()->admin->id)->with('customer.user', 'captain')->dynamicPaginate();

        return $this->responseSuccess(
            data: ['orders' => OrderResource::collection($orders)],
        );
    }

    public function store(Request $request)
    {
        //
    }

    public function show($orderId)
    {
        $order = $this->findAdminModel(auth()->user()->admin, Order::class, $orderId)->with([
            'customer.user',
            'captain:id,name,shipping_cost',
            'location:id,name,phone,address_type,lang,lat',
            'items',
            'items.product',
        ]);

        return $this->responseSuccess(
            data: ['order' => new OrderWithDetailsResource($order)],
        );
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($orderId)
    {
        $order = $this->findAdminModel(auth()->user()->admin, Order::class, $orderId);
        $order->delete();

        return $this->responseSuccess('order deleted');
    }

    public function changeStatus($orderId, Request $request)
    {
        $data = $request->validate([
            'status_id' => 'required|exists:statuses,id',
        ]);
        $order = $this->findAdminModel(auth()->user()->admin, Order::class, $orderId);

        $order->update([
            'status_id' => $data['status_id']
        ]);

        return $this->responseSuccess('status updated', new OrderWithDetailsResource($order));
    }
}
