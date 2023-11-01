<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\StoreService;
use Modules\Admin\Entities\Coupon;
use App\Http\Controllers\Controller;
use App\Actions\ValidateCouponPromocode;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Admin\Http\Requests\CouponRequest;
use Modules\Admin\Transformers\CouponResource;
use Modules\Admin\Http\Requests\UpdateCouponRequest;

class CouponController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('View-Coupon');
        $coupons = request()->store->coupons()->useFilters()->dynamicPaginate();

        return $this->responseSuccess('coupons', CouponResource::collection($coupons));
    }

    public function store(CouponRequest $request, ValidateCouponPromocode $action)
    {
        $this->authorize('Create-Coupon');
        $validatedData = $request->validated();
        $action->validatePromocode($request->promocode, request()->store);
        $coupon = request()->store->coupons()->create($validatedData);
        
        return $this->responseSuccess('coupon created', new CouponResource($coupon));
    }


    public function show($couponId)
    {
        $this->authorize('View-Coupon');
        $coupon = request()->store->coupons()->findOrFail($couponId);

        return new CouponResource($coupon);
    }

    public function update(UpdateCouponRequest $request, $couponId, ValidateCouponPromocode $action)
    {
        $this->authorize('Edit-Coupon');
        $coupon = request()->store->coupons()->findOrFail($couponId);
        $action->validateExestingPromocode($couponId, $request->promocode, request()->store);
        $coupon->update($request->validated());

        return $this->responseSuccess('coupon updated', new CouponResource($coupon));
    }

    public function destroy($couponId)
    {
        $this->authorize('Delete-Coupon');
        $coupon = request()->store->coupons()->findOrFail($couponId);
        $coupon->delete();

        return $this->responseSuccess('coupon deleted');
    }
}
