<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StoreService;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Admin\Entities\Tax;
use Modules\Admin\Http\Requests\Taxrequest;
use Modules\Admin\Http\Requests\UpdateTaxRequest;
use Modules\Admin\Transformers\TaxResource;

class TaxController extends Controller
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
        // $this->authorize('View-Tax');
        $taxes = $this->store->taxes()->useFilters()->dynamicPaginate();

        return $this->responseSuccess(data: TaxResource::collection($taxes));
    }

    public function store(Taxrequest $request)
    {
        // $this->authorize('Create-Tax');
        $tax = $this->store->taxes()->create($request->validated());

        return $this->responseSuccess('tax created', new TaxResource($tax));
    }

    public function show($taxId)
    {
        // $this->authorize('View-Tax');
        $tax = $this->getTax($taxId);

        return $this->responseSuccess(data: new TaxResource($tax));
    }

    public function update(UpdateTaxRequest $request, $taxId)
    {
        // $this->authorize('Edit-Tax');
        $data = $request->validated();
        $tax = $this->getTax($taxId);
        $tax->update($data);

        return $this->responseSuccess('tax updated', new TaxResource($tax));
    }

    public function destroy($taxId)
    {
        // $this->authorize('Delete-Tax');
        $tax = $this->getTax($taxId);
        $tax->delete();

        return $this->responseSuccess('tax deleted');
    }


    protected function getTax($taxId)
    {
        return $this->storeService->findStoreModel($this->store, Tax::class, $taxId);
    }
}
