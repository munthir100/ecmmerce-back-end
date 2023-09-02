<?php

namespace Modules\Admin\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderWithDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $formattedDate = Carbon::parse($this->created_at)->format('l, j F, Y');
        $formattedTime = Carbon::parse($this->created_at)->format('H:i:s');

        return [
            'order_number' => $this->id,
            'note' => $this->note,
            'date' => $formattedDate,
            'time' => $formattedTime,
            'status' => $this->status->name,
            'customer' => [
                'name' => $this->customer->user->name,
                'email' => $this->customer->user->email,
                'phone' => $this->customer->user->phone,
            ],
            'captain' => [
                'id' => $this->captain->id,
                'name' => $this->captain->name,
                'shipping_cost' => $this->captain->shipping_cost,
            ],
            'items' => $this->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'id' => $item->product->id,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'sku' => $item->product->sku,
                    'product_image' => $item->product->getProductMainImage(),
                ];
            }),
            'payment_type' => $this->payment_type,
            'total_price' => $this->total_price,
        ];
    }
}
