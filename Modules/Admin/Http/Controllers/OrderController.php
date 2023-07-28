<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Customer\Entities\Order;
use App\Http\Responses\MessageResponse;
use App\Traits\FindsModelsForAdmin;
use Illuminate\Contracts\Support\Renderable;
use Modules\Admin\Transformers\OrderResource;

class OrderController extends Controller
{
    use FindsModelsForAdmin;
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
        $order = $this->findModelOrFail(Order::class, $orderId);

        return new MessageResponse(
            data: ['product' => new OrderResource($order)],
            statusCode: 200
        );
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
