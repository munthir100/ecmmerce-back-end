<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Traits\ModelsForAdmin;
use App\Http\Responses\MessageResponse;
use Essa\APIToolKit\Api\ApiResponse;
use Modules\Admin\Entities\BankAccount;
use Modules\Admin\Http\Requests\BankAccountRequest;
use Modules\Admin\Http\Requests\UpdateBankAccountRequest;
use Modules\Admin\Transformers\BankAccountResource;

class BankAccountController extends Controller
{
    use ModelsForAdmin,ApiResponse;

    public function index()
    {
        $bankAccounts = BankAccount::useFilters()->with('bank')->ForAdmin(auth()->user()->admin->id)->dynamicPaginate();

        return $this->responseSuccess(
            data: ['bankAccounts' => BankAccountResource::collection($bankAccounts)],
        );
    }


    public function store(BankAccountRequest $request)
    {
        $data = $request->validated();
        $bankAccount = auth()->user()->admin->bankAccounts()->create($data);

        return $this->responseSuccess('bank account created', new BankAccountResource($bankAccount));
    }


    public function show($bankAccountId)
    {
        $bankAccount = $this->findAdminModel(auth()->user()->admin, BankAccount::class, $bankAccountId);

        return $this->responseSuccess(
            data: ['bank_account' => new BankAccountResource($bankAccount)],
        );
    }

    public function update(UpdateBankAccountRequest $request, $bankAccountId)
    {
        $data = $request->validated();
        $bankAccount = $this->findAdminModel(auth()->user()->admin, BankAccount::class, $bankAccountId);
        $bankAccount->update($data);

        return $this->responseSuccess(
            'bank account updated',
            ['bank_account' => new BankAccountResource($bankAccount)],
        );
    }


    public function destroy($bankAccountId)
    {
        $bankAccount = $this->findAdminModel(auth()->user()->admin, BankAccount::class, $bankAccountId);
        $bankAccount->delete();

        return $this->responseSuccess('bank account deleted');
    }
}
