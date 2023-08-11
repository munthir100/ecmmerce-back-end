<?php

namespace App\Actions;

use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class ValidateCouponPromocode
{
    public function validatePromocode($promocode, $store)
    {
        if ($store->coupons->contains('promocode', $promocode)) {
            Response::json(['message' => 'The promocode already exists in the store.'], 400)->throwResponse();
        }
    }
    public function validateExestingPromocode($couponId,$promocode, $store)
    {
        if ($store->coupons->where('id', '!=', $couponId)->contains('promocode', $promocode)) {
            Response::json(['message' => 'The promocode already exists in the store.'], 400)->throwResponse();
        }
    }
}
