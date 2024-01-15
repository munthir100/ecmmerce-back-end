<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\StoreService;
use Modules\Admin\Entities\Coupon;
use App\Http\Controllers\Controller;
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

        return $this->responseSuccess(data: [CouponResource::collection($coupons)]);
    }

    public function store(CouponRequest $request)
    {
        $this->authorize('Create-Coupon');
        $validatedData = $request->validated();
        $request->validatePromocodeIsUniqueInStore(request()->store);
        $coupon = request()->store->coupons()->create($validatedData);

        return $this->responseSuccess('coupon created', new CouponResource($coupon));
    }


    public function show($couponId)
    {
        $this->authorize('View-Coupon');
        $coupon = request()->store->coupons()->findOrFail($couponId);

        return new CouponResource($coupon);
    }

    public function update(UpdateCouponRequest $request, $couponId)
    {
        $this->authorize('Edit-Coupon');
        $coupon = request()->store->coupons()->findOrFail($couponId);
        $request->validatePromocodeIsUniqueInStore(request()->store, $couponId);
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
