<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SellerService;
use App\Http\Controllers\Controller;
use Modules\Admin\Transformers\SellerResource;
use Modules\Admin\Http\Requests\CreateSellerRequest;

class SellerManagementController extends Controller
{
    protected $sellerService;

    public function __construct(SellerService $sellerService)
    {
        $this->sellerService = $sellerService;
    }


    public function index()
    {
        $admin = $this->getAdmin();
        $sellers = $admin->sellers()->with('user', 'user.roles', 'user.permissions')->useFilters()->dynamicPaginate();

        return $this->responseSuccess(data: [SellerResource::collection($sellers)]);
    }

    public function store(CreateSellerRequest $request)
    {
        $data = $request->validated();
        $admin = $this->getAdmin();
        $store = $admin->store;


        $user = $this->sellerService->createSellerUser($data);
        $this->sellerService->createSeller($admin, $store, $user);
        [$role, $permissions] = $this->sellerService->assignRoleAndPermissions($data, $user);
        $responseData = $this->sellerService->prepareResponseData($user, $role, $permissions);

        return $this->responseCreated('Seller created', $responseData);
    }

    public function show($sellerId)
    {
        $admin = $this->getAdmin();
        $seller = $admin->sellers()->findOrFail($sellerId);

        return $this->responseSuccess('seller', new SellerResource($seller));
    }

    public function update(Request $request, $sellerId)
    {
        $admin = $this->getAdmin();
        $seller = $admin->sellers()->findOrFail($sellerId);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $seller->user->id,
            'role' => 'required|string',
            'permissions' => 'nullable|array',
        ]);

        $seller->user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        [$role, $permissions] = $this->sellerService->assignRoleAndPermissions($data, $seller->user);
        $responseData = $this->sellerService->prepareResponseData($seller->user, $role, $permissions);

        return $this->responseSuccess('Seller updated', $responseData);
    }

    public function destroy($sellerId)
    {
        $admin = $this->getAdmin();
        $seller = $admin->sellers()->findOrFail($sellerId);
        $seller->user->delete();

        return $this->responseSuccess('Seller deleted');
    }

    protected function getAdmin()
    {
        return request()->user()->admin;
    }
}
