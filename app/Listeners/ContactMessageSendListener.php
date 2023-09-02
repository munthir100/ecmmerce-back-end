<?php

namespace App\Listeners;

use App\Events\ContactMessageSend;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Admin\Entities\AdminNotification;

class ContactMessageSendListener
{

    public function handle(ContactMessageSend $event): void
    {
        $contactMessage = $event->contactMessage;

        AdminNotification::create([
            'title' => 'New message',
            'details' => 'New message from website by: ' . $contactMessage->name,
            'admin_id' => $contactMessage->admin_id
        ]);
    }
}
