<?php

namespace Modules\Admin\Http\Requests;

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
            'cash_on_delivery_cost' => [
                'required_if:cash_on_delivery,true',
                'integer',
            ],
            'city_id' => 'sometimes|array',
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
