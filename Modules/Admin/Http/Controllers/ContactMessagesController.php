<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Transformers\ContactMessageResource;

class ContactMessagesController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('View-Contact-Message');
        $admin = $this->getAdmin();
        $contactMessages = $admin->contactMessages()->get();

        return $this->responseSuccess(data: [ContactMessageResource::collection($contactMessages)]);
    }


    
    public function destroy($messageId)
    {
        $this->authorize('Delete-Contact-Message');
        $admin = $this->getAdmin();
        $message = $admin->contactMessages()->find($messageId);

        if (!$message) {
            return $this->responseNotFound('message not found');
        }
        $message->delete();

        return $this->responseSuccess('message deleted');
    }


    protected function getAdmin()
    {
        return request()->user()->admin;
    }
}