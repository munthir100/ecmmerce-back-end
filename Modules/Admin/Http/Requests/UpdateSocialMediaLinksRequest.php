<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSocialMediaLinksRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'facebook' => 'nullable|string',
            'snapchat' => 'nullable|string',
            'twitter' => 'nullable|string',
            'tictok' => 'stringnullable|',
            'whatsapp' => 'nullable|string',
            'maroof' => 'stringnullable|',
            'instagram' => 'nullable|string',
            'telegram' => 'nullable|string',
            'google_play' => 'nullable|string',
            'app_store' => 'nullable|string',
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
