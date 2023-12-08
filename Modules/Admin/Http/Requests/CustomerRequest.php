<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Customer\Rules\UniqueEmailForCustomer;
use Modules\Customer\Rules\UniquePhoneForCustomer;

class CustomerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => ['required'],
            'phone' => ['required'],
            'password' => 'required',
            'birth_date' => 'date',
            'gender' => 'required',
            'description' => 'sometimes|string',
            'city_id' => 'required|exists:cities,id',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateEmailAndPhoneInStore(request()->store);
        });
    }

    function validateEmailAndPhoneInStore($store){
        
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
