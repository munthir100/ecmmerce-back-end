<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Modules\Admin\Transformers\AdminNotificationResource;

class AdminNotificationController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('View-Notification');
        $admin = $this->getAdmin();
        $notifications = $admin->notifications()->get();

        return $this->responseSuccess(data: AdminNotificationResource::collection($notifications));
    }


    
    public function destroy($notificationId)
    {
        $this->authorize('Delete-Notification');
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
        return request()->user()->admin;
    }
}
