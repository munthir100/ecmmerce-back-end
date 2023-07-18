<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Customer\Rules\UniqueEmailForCustomer;
use Modules\Customer\Rules\UniquePhoneForCustomer;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes',
            'email' => 'sometimes',
            'phone' => ['sometimes'],
            'password' => 'sometimes',
            'birth_date' => 'sometimes|date',
            'gender' => 'sometimes',
            'description' => 'string',
            'city_id' => 'sometimes|exists:cities,id',
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
