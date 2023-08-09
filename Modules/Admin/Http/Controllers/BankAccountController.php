<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Traits\ModelsForAdmin;
use App\Http\Responses\MessageResponse;
use Modules\Admin\Entities\BankAccount;
use Modules\Admin\Http\Requests\BankAccountRequest;
use Modules\Admin\Http\Requests\UpdateBankAccountRequest;
use Modules\Admin\Transformers\BankAccountResource;

class BankAccountController extends Controller
{
    use ModelsForAdmin;

    public function index()
    {
        $perPage = request()->get('perPage', 25);
        $adminId = request()->user()->admin->id;
        $bankAccounts = BankAccount::with('bank')->ForAdmin($adminId)->paginate($perPage);

        return new MessageResponse(
            data: ['bankAccounts' => BankAccountResource::collection($bankAccounts)],
            statusCode: 200
        );
    }


    public function store(BankAccountRequest $request)
    {
        $data = $request->validated();
        $data['admin_id'] = request()->user()->admin->id;
        $bankAccount = BankAccount::create($data);

        return new MessageResponse('bank account created', new BankAccountResource($bankAccount), 200);
    }


    public function show($bankAccountId)
    {
        $bankAccount = $this->findAdminModel(BankAccount::class, $bankAccountId);

        return new MessageResponse(
            data: ['bank_account' => new BankAccountResource($bankAccount)],
            statusCode: 200
        );
    }

    public function update(UpdateBankAccountRequest $request, $bankAccountId)
    {
        $data = $request->validated();
        $bankAccount = $this->findAdminModel(BankAccount::class, $bankAccountId);
        $bankAccount->update($data);

        return new MessageResponse(
            'bank account updated',
            ['bank_account' => new BankAccountResource($bankAccount)],
            200
        );
    }


    public function destroy($bankAccountId)
    {
        $bankAccount = $this->findAdminModel(BankAccount::class, $bankAccountId);
        $bankAccount->delete();

        return new MessageResponse(
            'bank account deleted',
            statusCode: 200
        );
    }
}
