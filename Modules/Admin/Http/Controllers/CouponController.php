<?php

namespace Modules\Admin\Http\Controllers;

use App\Traits\ModelsForAdmin;
use Illuminate\Validation\Rule;
use Modules\Admin\Entities\Coupon;
use App\Http\Controllers\Controller;
use App\Http\Responses\MessageResponse;
use App\Actions\ValidateCouponPromocode;
use Essa\APIToolKit\Api\ApiResponse;
use Modules\Admin\Http\Requests\CouponRequest;
use Modules\Admin\Transformers\CouponResource;
use Modules\Admin\Http\Requests\UpdateCouponRequest;

class CouponController extends Controller
{
    use ModelsForAdmin, ApiResponse;

    public function index()
    {
        $coupons = Coupon::useFilters()->ForAdmin(auth()->user()->admin->id)->dynamicPaginate();

        return new MessageResponse('coupons', CouponResource::collection($coupons));
    }

    public function store(CouponRequest $request, ValidateCouponPromocode $action)
    {
        $validatedData = $request->validated();
        $store = $request->user()->admin->store;
        $action->validatePromocode($request->promocode, $store);
        $validatedData['store_id'] = $store->id;
        $coupon = Coupon::create($validatedData);

        return $this->responseSuccess('coupon created',new CouponResource($coupon));
    }


    public function show($couponId)
    {
        $coupon = $this->findAdminModel(auth()->user()->admin,Coupon::class, $couponId);
        
        return new CouponResource($coupon);
    }

    public function update(UpdateCouponRequest $request, $couponId, ValidateCouponPromocode $action)
    {
        $coupon = $this->findAdminModel(auth()->user()->admin,Coupon::class, $couponId);
        $store = $request->user()->admin->store;
        $action->validateExestingPromocode($couponId, $request->promocode, $store);
        $coupon->update($request->validated());

        return $this->responseSuccess('coupon updated',new CouponResource($coupon));
    }

    public function destroy($couponId)
    {
        $coupon = $this->findAdminModel(auth()->user()->admin,Coupon::class, $couponId);
        $coupon->delete();

        return $this->responseSuccess('coupon deleted');
    }
}
