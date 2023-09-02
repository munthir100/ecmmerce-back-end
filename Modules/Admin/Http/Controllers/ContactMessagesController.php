<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Transformers\ContactMessageResource;

class ContactMessagesController extends Controller
{
    public function index()
    {
        $admin = $this->getAdmin();
        $contactMessages = $admin->contactMessages()->get();

        return $this->responseSuccess(data: ContactMessageResource::collection($contactMessages));
    }


    
    public function destroy($messageId)
    {
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
        return auth()->user()->admin;
    }
}