<?php

namespace Modules\Admin\Transformers\Settings;

use Illuminate\Http\Resources\Json\JsonResource;

class SocialMediaLinksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'facebook' => $this->facebook,
            'snapchat' => $this->snapchat,
            'twitter' => $this->twitter,
            'tictok' => $this->tictok,
            'whatsapp' => $this->whatsapp,
            'maroof' => $this->maroof,
            'instagram' => $this->instagram,
            'telegram' => $this->telegram,
            'google_play' => $this->google_play,
            'app_store' => $this->app_store,
        ];
    }
}
