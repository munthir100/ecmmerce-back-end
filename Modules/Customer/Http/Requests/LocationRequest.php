<?php

namespace Modules\Customer\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
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
            'lang' => 'required|string',
            'lat' => 'required|string',
            'phone' => 'required|string',
            'address_type' => 'required',
        ];
    }

    public function validateStoreCity($store)
    {
        return $this->validate([
            'city_id' => [
                'required',
                Rule::exists('cities', 'id')->where(function ($query) use ($store) {
                    $query->whereIn('country_id', $store->countries->pluck('id'));
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
