<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaptainRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'shipping_cost' => 'required|numeric',
            'cash_on_delivery' => 'boolean',
            'cash_on_delivery_cost' => [
                'required_if:cash_on_delivery,true',
                'integer',
            ],
            'expected_time_shipping' => 'required|integer',
            'is_active' => 'boolean',
            'city_id' => 'required|array',
            'city_id.*' => 'exists:cities,id',
        ];
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
