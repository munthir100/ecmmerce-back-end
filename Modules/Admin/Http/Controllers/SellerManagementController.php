<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StoreService;
use App\Traits\ModelsForAdmin;
use App\Services\SellerService;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Seller;
use App\Http\Responses\MessageResponse;
use Modules\Admin\Transformers\SellerResource;
use Modules\Admin\Http\Requests\CreateSellerRequest;

class SellerManagementController extends Controller
{
    protected $storeService, $store, $sellerService;

    public function __construct(SellerService $sellerService, StoreService $storeService)
    {
        $this->storeService = $storeService;
        $this->store = $this->storeService->getStore();
        $this->sellerService = $sellerService;
    }


    public function index()
    {
        $sellers = Seller::forAdmin(auth()->user()->admin->id)->useFilters()->dynamicPaginate();

        return $this->responseSuccess('sellers', SellerResource::collection($sellers));
    }

    public function store(CreateSellerRequest $request)
    {
        $data = $request->validated();
        $admin = $request->user()->admin;
        $store = $admin->store;

        $this->sellerService->validateEmail($data['email'], $admin);

        $user = $this->sellerService->createSellerUser($data);
        $this->sellerService->createSeller($admin, $store, $user);
        [$role, $permissions] = $this->sellerService->assignRoleAndPermissions($data, $user);
        $responseData = $this->sellerService->prepareResponseData($user, $role, $permissions);

        return $this->responseCreated('Seller created', $responseData);
    }

    public function show($sellerId)
    {
        $seller = $this->findAdminModel(auth()->user()->admin, Seller::class, $sellerId);

        return $this->responseSuccess('seller', new SellerResource($seller));
    }

    public function update(Request $request, $sellerId)
    {
        $seller = $this->findAdminModel(auth()->user()->admin, Seller::class, $sellerId);

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
        $seller = $this->findAdminModel(auth()->user()->admin, Seller::class, $sellerId);

        $seller->user->delete();

        return $this->responseSuccess('Seller deleted');
    }
}
