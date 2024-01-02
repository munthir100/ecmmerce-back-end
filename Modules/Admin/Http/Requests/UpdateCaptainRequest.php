<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCaptainRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|string',
            'shipping_cost' => 'sometimes|numeric',
            'expected_time_shipping' => 'sometimes|integer',
            'cash_on_delivery' => 'sometimes|boolean',
            'is_active' => 'boolean',
            'cash_on_delivery_cost' => [
                'required_if:cash_on_delivery,true',
                'integer',
            ],
            'is_active' => 'boolean',
            'city_id' => 'sometimes|array',
            'city_id.*' => 'distinct',
        ];
    }

    public function validateStoreCity($store)
    {
        return $this->validate([
            'city_id' => [
                'array',
                Rule::exists('cities', 'id')->where(function ($query) use ($store) {
                    $query->whereIn('country_id', $store->countries->pluck('id'));
                }),
            ],
            'city_id.*' => 'distinct',
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
