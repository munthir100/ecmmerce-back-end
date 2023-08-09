<?php

namespace Modules\Admin\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BankAccountResource extends JsonResource
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
            'id' => $this->id,
            'holder_name' => $this->holder_name,
            'details' => $this->details,
            'iban' => $this->iban,
            'account_number' => $this->account_number,
            'bank' => $this->bank->name,
        ];
    }
}
