<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'link' => 'string|max:255',
            'description' => 'string|max:255',
            'store_logo' => 'image',
            'store_icon' => 'image',
            'language_id' => 'exists:languages,id',
            'is_active' => 'boolean',
            'maintenance_message' => ['required_if:is_active,true', 'string', 'max:255'],
            'button_color' => 'string',
            'text_color' => 'string',
        ];
    }

    public function validateStoreCity($store)
    {
        return $this->validate([
            'city_id' => [
                'required',
                'array',
                Rule::exists('cities', 'id')->where(function ($query) use ($store) {
                    $query->whereIn('country_id', $store->countries->pluck('id'));
                }),
            ],
            'city_id.*' => 'distinct',
        ]);
    }
    public function validateStoreLink($store)
    {
        return $this->validate([
            'link' => [
                Rule::unique('stores')->ignore($store),
            ],
        ]);
    }
    public function validateCommercialRegistration($store)
    {
        return $this->validate([
            'commercial_registration_no' => Rule::unique('stores')->ignore($store),
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
