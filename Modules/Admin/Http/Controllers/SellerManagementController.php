<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ModelsForAdmin;
use App\Services\SellerService;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Seller;
use App\Http\Responses\MessageResponse;
use Modules\Admin\Transformers\SellerResource;
use Modules\Admin\Http\Requests\CreateSellerRequest;

class SellerManagementController extends Controller
{
    use ModelsForAdmin;

    private $sellerService;

    public function __construct(SellerService $sellerService)
    {
        $this->sellerService = $sellerService;
    }

    public function index()
    {
        $term = request()->get('term', '');
        $perPage = request()->get('perPage', 25);
        $sellers = $this->getAdminModels(Seller::class, $term, $perPage);

        return new MessageResponse('sellers', SellerResource::collection($sellers), 200);
    }

    public function store(CreateSellerRequest $request)
    {
        $data = $request->validated();
        $admin = $request->user()->admin;
        $store = $admin->store;
        $user = $this->sellerService->validateEmail($data['email'],$store);
        $user = $this->sellerService->createSellerUser($data);
        $this->sellerService->createSeller($admin, $store, $user);
        [$role, $permissions] = $this->sellerService->assignRoleAndPermissions($data, $user);
        $responseData = $this->sellerService->prepareResponseData($user, $role, $permissions);

        return new MessageResponse('Seller created', $responseData, 200);
    }

    public function show($sellerId)
    {
        $seller = $this->findAdminModel(Seller::class, $sellerId);

        return new MessageResponse('seller', new SellerResource($seller), 200);
    }

    public function update(Request $request, $sellerId)
    {
        $seller = $this->findAdminModel(Seller::class, $sellerId);

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
        
        return new MessageResponse('Seller updated', $responseData, 200);
    }

    public function destroy($sellerId)
    {
        $seller = $this->findAdminModel(Seller::class, $sellerId);

        $seller->user->delete();

        return new MessageResponse('Seller deleted', [], 200);
    }
}
