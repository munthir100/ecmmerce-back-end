<?php

namespace Modules\Admin\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductWithOptionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'store_id' => $this->store_id,
            'name' => $this->name,
            'sku' => $this->sku,
            'quantity' => $this->unspecified_quantity ? 'unspecified' : $this->quantity,
            'wheight' => $this->wheight,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'price' => $this->price,
            'cost' => $this->cost,
            'discount' => $this->discount,
            'free_shipping' => $this->free_shipping,
            'is_active' => $this->is_active,
            'product_images' => $this->resource->retrieveMedia(),
            'options' => ProductOptionResource::collection($this->options),
        ];
    }
}
