<?php

namespace Modules\Admin\Http\Controllers;

use App\Traits\ModelsForAdmin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Entities\BankAccount;
use Modules\Admin\Http\Requests\BankAccountRequest;
use Modules\Admin\Transformers\BankAccountResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Admin\Http\Requests\UpdateBankAccountRequest;

class BankAccountController extends Controller
{
    use ModelsForAdmin, AuthorizesRequests;

    public function index()
    {
        $this->authorize('View-Payment-Methods');
        $store = $this->getStore();
        $bankAccounts = BankAccount::with('bank')->where('admin_id', $store->admin_id)->useFilters()->dynamicPaginate();

        return $this->responseSuccess(
            data: [BankAccountResource::collection($bankAccounts)],
        );
    }


    public function store(BankAccountRequest $request)
    {
        $data = $request->validated();
        $bankAccount = request()->user()->admin->bankAccounts()->create($data);

        return $this->responseSuccess('bank account created', new BankAccountResource($bankAccount));
    }


    public function show($bankAccountId)
    {
        $this->authorize('View-Payment-Methods');
        $store = $this->getStore();
        $bankAccount =  BankAccount::where('admin_id', $store->admin_id)->find($bankAccountId);

        return $this->responseSuccess(
            data: ['bank_account' => new BankAccountResource($bankAccount)],
        );
    }

    public function update(UpdateBankAccountRequest $request, $bankAccountId)
    {
        $data = $request->validated();
        $bankAccount = $this->findAdminModel(request()->user()->admin, BankAccount::class, $bankAccountId);
        $bankAccount->update($data);

        return $this->responseSuccess(
            'bank account updated',
            ['bank_account' => new BankAccountResource($bankAccount)],
        );
    }


    public function destroy($bankAccountId)
    {
        $bankAccount = $this->findAdminModel(request()->user()->admin, BankAccount::class, $bankAccountId);
        $bankAccount->delete();

        return $this->responseSuccess('bank account deleted');
    }


    protected function getStore()
    {
        if (auth()->check()) {
            $user = Auth::user();

            if ($user->isAdmin) {
                return $user->admin->store;
            } else {
                return $user->seller->store;
            }
        }
    }
}
