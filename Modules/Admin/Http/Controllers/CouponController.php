<?php

namespace Modules\Admin\Http\Controllers;

use App\Traits\ModelsForAdmin;
use Illuminate\Validation\Rule;
use Modules\Admin\Entities\Coupon;
use App\Http\Controllers\Controller;
use App\Http\Responses\MessageResponse;
use App\Actions\ValidateCouponPromocode;
use Modules\Admin\Http\Requests\CouponRequest;
use Modules\Admin\Transformers\CouponResource;
use Modules\Admin\Http\Requests\UpdateCouponRequest;

class CouponController extends Controller
{
    use ModelsForAdmin;
    public function index()
    {
        $term = request()->get('term', '');
        $perPage = request()->get('perPage', 25);
        $coupons = $this->getAdminModels(Coupon::class, $term, $perPage);

        return new MessageResponse('coupons', CouponResource::collection($coupons));
    }

    public function store(CouponRequest $request,ValidateCouponPromocode $action)
    {
        $validatedData = $request->validated();
        $store = $request->user()->admin->store;
        $action->validatePromocode($request->promocode, $store);
        $validatedData['store_id'] = $store->id;
        $coupon = Coupon::create($validatedData);

        return new CouponResource($coupon);
    }


    public function show($couponId)
    {
        $coupon = $this->findAdminModel(Coupon::class, $couponId);
        return new CouponResource($coupon);
    }

    public function update(UpdateCouponRequest $request,$couponId ,ValidateCouponPromocode $action)
    {
        $coupon = $this->findAdminModel(Coupon::class, $couponId);
        $store = $request->user()->admin->store;
        $action->validateExestingPromocode($couponId,$request->promocode, $store);
        $coupon->update($request->validated());
        return new CouponResource($coupon);
    }

    public function destroy($couponId)
    {
        $coupon = $this->findAdminModel(Coupon::class, $couponId);
        $coupon->delete();

        return response()->json(['message' => 'Coupon deleted']);
    }

    
}
