<?php

namespace Modules\Customer\Http\Controllers;

use Modules\Store\Entities\Store;
use App\Events\ContactMessageSend;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\ContactMessage;
use Modules\Customer\Http\Requests\SendCustomerMessageRequest;

class CustomerController extends Controller
{
    function sendMessage(Store $store, SendCustomerMessageRequest $request)
    {
        $data = $request->validated();
        $data['source'] = 'web';
        $data['admin_id'] = $store->admin_id;
        $contactMessage = ContactMessage::create($data);
        event(new ContactMessageSend($contactMessage));

        return $this->responseSuccess('contact message send', $contactMessage);
    }
}
