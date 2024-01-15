<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StoreService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Admin\Entities\Tax;
use Modules\Admin\Http\Requests\Taxrequest;
use Modules\Admin\Http\Requests\UpdateTaxRequest;
use Modules\Admin\Transformers\TaxResource;

class TaxController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        // $this->authorize('View-Tax');
        $taxes = request()->store->taxes()->useFilters()->dynamicPaginate();

        return $this->responseSuccess(data: [TaxResource::collection($taxes)]);
    }

    public function store(Taxrequest $request)
    {
        // $this->authorize('Create-Tax');
        $tax = request()->store->taxes()->create($request->validated());

        return $this->responseSuccess('tax created', new TaxResource($tax));
    }

    public function show($taxId)
    {
        // $this->authorize('View-Tax');
        $tax = request()->store->taxes()->findOrFail($taxId);

        return $this->responseSuccess(data: new TaxResource($tax));
    }

    public function update(UpdateTaxRequest $request, $taxId)
    {
        // $this->authorize('Edit-Tax');
        $data = $request->validated();
        $tax = request()->store->taxes()->findOrFail($taxId);
        $tax->update($data);

        return $this->responseSuccess('tax updated', new TaxResource($tax));
    }

    public function destroy($taxId)
    {
        // $this->authorize('Delete-Tax');
        $tax = request()->store->taxes()->findOrFail($taxId);
        $tax->delete();

        return $this->responseSuccess('tax deleted');
    }
}
