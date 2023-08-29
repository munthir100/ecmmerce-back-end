<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'              => 'sometimes',
            'sku'               => 'sometimes',
            'quantity'          => 'sometimes',
            'wheight'           => 'sometimes',
            'short_description' => 'sometimes|max:20',
            'description'       => 'sometimes',
            'price'             => 'sometimes|numeric',
            'cost'              => 'sometimes',
            'discount'          => 'sometimes',
            'free_shipping'     => 'sometimes',
            'main_image'        => 'sometimes|image',
            'sub_images.*'      => 'sometimes',
            'is_active'         => 'sometimes|boolean',
            'category_id'       => 'nullable|integer',
            'options'                  => 'sometimes|array',
            'options.*.name'           => 'required_with:options|string',
            'options.*.values'         => 'required_with:options|array',
            'options.*.values.*.name'  => 'required_with:options.*.values|string',
            'options.*.values.*.additional_price' => 'numeric',
            'options.*.values.*.quantity'         => 'nullable|integer',
        ];
    }

    public function validateSkuIsUnique($store, $product)
    {
        return $this->validate([
            'sku' => [
                Rule::unique('products', 'sku')->where(function ($query) use ($store, $product) {
                    return $query->where('store_id', $store->id)
                        ->where('id', '!=', $product->id);
                }),
            ],
        ]);
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
