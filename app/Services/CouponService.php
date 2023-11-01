<?php

namespace App\Services;

use Modules\Admin\Entities\Coupon;
use Modules\Customer\Entities\Order;
use Modules\Customer\Entities\CouponUsage;
use Illuminate\Validation\ValidationException;

class CouponService
{
    public function findByPromocode($store, $promocode): Coupon
    {
        $coupon = $store->coupons()->where('promocode', $promocode)->first();
        if (!$coupon) {
            abort(response()->json([
                'message' => 'coupon not found',
                'success' => false,
                'statuscode' => 404,
            ]));
        }

        return $coupon;
    }

    public function applyCouponDiscount(Order $order, Coupon $coupon, $orderItems, $store)
    {
        // Check if the coupon is valid and applicable to the order
        $applicabilityReason = $this->isCouponApplicable($order, $coupon, $orderItems, $store);

        if ($applicabilityReason) {
            throw ValidationException::withMessages(['coupon' => $applicabilityReason]);
        }

        $couponValue = $coupon->value;

        if ($coupon->discount_type === 'percentage') {
            $couponValue = ($order->total_price * $coupon->value) / 100;
        }

        $order->total_price -= $couponValue;
        $coupon->update(['used_times' => $coupon->used_times + 1]);
        $this->saveCouponUsage($coupon, $order->customer_id);

        return $order;
    }

    public function isCouponApplicable(Order $order, Coupon $coupon, $orderItems, $store)
    {
        if ($coupon->total_usage_times <= $coupon->used_times) {
            return 'Coupon has reached its total usage limit';
        }

        $customerUsageCount = CouponUsage::where('customer_id', $order->customer_id)
            ->where('coupon_id', $coupon->id)
            ->count();

        if ($customerUsageCount >= $coupon->usage_per_customer) {
            return 'Coupon has reached its usage limit per customer';
        }

        if ($coupon->discount_end_date && now() > $coupon->discount_end_date) {
            return 'Coupon has expired';
        }

        if ($coupon->store_id && $coupon->store_id !== $order->store_id) {
            return 'Coupon is not applicable to this store';
        }

        if ($coupon->exclude_discounted_products) {
            // Get product IDs of discounted products
            $discountedProductIds = $store->products()->discounted()->pluck('id')->toArray();
            // Get product IDs in the current order
            $orderProductIds = array_map(function ($orderItem) {
                return $orderItem->product_id;
            }, $orderItems);

            // Check for intersection between discounted products and products in the order
            $excludedProductIds = array_intersect($orderProductIds, $discountedProductIds);

            if (!empty($excludedProductIds)) {
                return 'Coupon excludes some discounted products';
            }
        }

        if ($coupon->minimum_purchase && $order->total_price < $coupon->minimum_purchase) {
            return 'Order total does not meet the minimum purchase requirement';
        }

        return null; // Coupon is applicable
    }

    protected function saveCouponUsage(Coupon $coupon, $customerId)
    {
        $couponUsage = new CouponUsage([
            'coupon_id' => $coupon->id,
            'customer_id' => $customerId,
        ]);
        $couponUsage->save();
    }
}
