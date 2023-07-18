<?php

namespace Modules\Customer\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Admin\Transformers\ProductResource;

class shoppingCartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $products = $this->products->map(function ($product) {
            return [
                'product' => new ProductResource($product),
                'quantity' => $product->pivot->quantity,
            ];
        });

        return [
            'id' => $this->id,
            'products' => $products,
        ];
    }
}
