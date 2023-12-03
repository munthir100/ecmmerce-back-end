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
            'link' => [
                Rule::unique('stores')->ignore(request()->store),
            ],
            'description' => 'string|max:255',
            'store_logo' => 'image',
            'store_icon' => 'image',
            'language_id' => 'exists:languages,id',
            'is_active' => 'boolean',
            'maintenance_message' => ['required_if:is_active,false', 'string', 'max:255'],
            'button_color' => 'string',
            'text_color' => 'string',
            'city_id' => [
                'array',
                Rule::exists('cities', 'id')->where(function ($query) {
                    $query->whereIn('country_id', request()->store->countries->pluck('id'));
                }),
            ],
            'city_id.*' => 'distinct',
            'commercial_registration_no' => Rule::unique('stores')->ignore(request()->store),
            'store_theme_id' => 'exists:store_themes,id'
        ];
    }

    function messages()
    {
        return [
            'maintenance_message.required_if' => __("the mantinance message is required when you try de-activate store"),
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
