<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                     => 'required',
            'sku'                      => 'required',
            'is_virtual_product'       => 'sometimes|boolean',
            'has_unspecified_quantity' => 'sometimes|boolean',
            'quantity'                 => request()->input('has_unspecified_quantity') ? 'nullable' : 'required|integer',
            'weight'                   => 'sometimes',
            'short_description'        => 'sometimes|max:20',
            'description'              => 'sometimes',
            'price'                    => 'required|numeric',
            'cost'                     => 'sometimes',
            'is_discounted'            => 'sometimes',
            'price_after_discount'     => 'required_with:is_discounted|numeric',
            'free_shipping'            => 'sometimes',
            'main_image'               => 'required|image',
            'sub_images.*'             => 'sometimes|image',
            'is_active'                => 'required|boolean',
            'category_id'              => 'sometimes|exists:categories,id',
            'brand_id'                 => 'required_with:category_id|exists:brands,id',

            'options'                  => 'sometimes|array',
            'options.*.name'           => 'required_with:options|string',
            'options.*.values'         => 'required_with:options|array',
            'options.*.values.*.name'  => 'required_with:options.*.values|string',
            'options.*.values.*.additional_price' => 'numeric',
            'options.*.values.*.quantity'         => 'nullable|integer',
        ];
    }

    public function validateSkuIsUnique($store)
    {
        return $this->validate([
            'sku' => [
                Rule::unique('products', 'sku')->where(function ($query) use ($store) {
                    return $query->where('store_id', $store->id);
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
