<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Customer\Entities\Order;
use App\Http\Responses\MessageResponse;
use App\Traits\ModelsForAdmin;
use Illuminate\Contracts\Support\Renderable;
use Modules\Admin\Transformers\OrderResource;
use Modules\Admin\Transformers\OrderWithDetailsResource;

class OrderController extends Controller
{
    use ModelsForAdmin;
    public function index()
    {
        $term = request()->get('term', '');
        $perPage = request()->get('perPage', 25);
        $adminId = request()->user()->admin->id;
        $orders = Order::search($term)->ForAdmin($adminId)
            ->with('customer.user', 'captain')->paginate($perPage);

        return new MessageResponse(
            data: ['orders' => OrderResource::collection($orders)],
            statusCode: 200
        );
    }

    public function store(Request $request)
    {
        //
    }

    public function show($orderId)
    {
        $order = Order::with([
            'customer.user',
            'captain:id,name,shipping_cost',
            'location:id,name,phone,address_type,lang,lat',
            'items',
            'items.product',
        ])->find($orderId);

        return new MessageResponse(
            data: ['order' => new OrderWithDetailsResource($order)],
            statusCode: 200
        );
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($orderId)
    {
        $order = $this->findAdminModel(Order::class, $orderId);

        $order->delete();
        return new MessageResponse('order deleted', statusCode: 200);
    }

    public function changeStatus($orderId, Request $request)
    {
        $data = $request->validate([
            'status_id' => 'required|exists:statuses,id',
        ]);
        $order = $this->findAdminModel(Order::class, $orderId);

        $order->update([
            'status_id' => $data['status_id']
        ]);

        return new MessageResponse('status updated', new OrderWithDetailsResource($order), 200);
    }
}
