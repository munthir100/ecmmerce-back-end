<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\StoreService;
use Modules\Admin\Entities\Coupon;
use App\Http\Controllers\Controller;
use App\Http\Responses\MessageResponse;
use App\Actions\ValidateCouponPromocode;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Admin\Http\Requests\CouponRequest;
use Modules\Admin\Transformers\CouponResource;
use Modules\Admin\Http\Requests\UpdateCouponRequest;

class CouponController extends Controller
{
    use AuthorizesRequests;
    protected $storeService, $store;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
        $this->store = $this->storeService->getStore();
    }

    public function index()
    {
        $this->authorize('View-Coupon');
        $coupons = $this->store->coupons()->useFilters()->dynamicPaginate();

        return new MessageResponse('coupons', CouponResource::collection($coupons));
    }

    public function store(CouponRequest $request, ValidateCouponPromocode $action)
    {
        $this->authorize('Create-Coupon');
        $validatedData = $request->validated();
        $action->validatePromocode($request->promocode, $this->store);
        $coupon = $this->store->coupons()->create($validatedData);
        
        return $this->responseSuccess('coupon created', new CouponResource($coupon));
    }


    public function show($couponId)
    {
        $this->authorize('View-Coupon');
        $coupon = $this->storeService->findStoreModel($this->store, Captain::class, $couponId);

        return new CouponResource($coupon);
    }

    public function update(UpdateCouponRequest $request, $couponId, ValidateCouponPromocode $action)
    {
        $this->authorize('Edit-Coupon');
        $coupon = $this->storeService->findStoreModel($this->store, Captain::class, $couponId);
        $action->validateExestingPromocode($couponId, $request->promocode, $this->store);
        $coupon->update($request->validated());

        return $this->responseSuccess('coupon updated', new CouponResource($coupon));
    }

    public function destroy($couponId)
    {
        $this->authorize('Delete-Coupon');
        $coupon = $this->storeService->findStoreModel($this->store, Captain::class, $couponId);
        $coupon->delete();

        return $this->responseSuccess('coupon deleted');
    }
}
