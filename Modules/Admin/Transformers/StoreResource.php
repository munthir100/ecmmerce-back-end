<?php

namespace Modules\Admin\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'link' => $this->link,
            'default_currency' => $this->default_currency,
            'language_id' => $this->language_id,
            'is_active' => $this->is_active,
            'store_theme_id' => $this->store_theme_id, // for store module
            'commercial_registration_no' => $this->commercial_registration_no, // for store module
            'button_color' => $this->button_color, // for store module
            'text_color' => $this->text_color, // for store module
            'banner_color' => $this->banner_color, // for store module
            'banner_content' => $this->banner_content, // for store module
            'banner_link' => $this->banner_link, // for store module
            'logo' => $this->resource->retrieveMedia(),
        ];

        if (!$this->is_active) {
            $data['maintenance_message'] = $this->maintenance_message;
        }

        return $data;
    }
}
