<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Admin\Transformers\AdminNotificationResource;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $admin = $this->getAdmin();
        $notifications = $admin->notifications()->get();

        return $this->responseSuccess(data: AdminNotificationResource::collection($notifications));
    }


    
    public function destroy($notificationId)
    {
        $admin = $this->getAdmin();
        $notification = $admin->notifications()->find($notificationId);

        if (!$notification) {
            return $this->responseNotFound('notification not found');
        }
        $notification->delete();

        return $this->responseSuccess('notification deleted');
    }


    protected function getAdmin()
    {
        return auth()->user()->admin;
    }
}
