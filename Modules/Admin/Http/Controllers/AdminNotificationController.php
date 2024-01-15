<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Controller;
use Modules\Admin\Transformers\AdminNotificationResource;

class AdminNotificationController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('View-Notification');
        $notifications = request()->admin->notifications()->get();

        return $this->responseSuccess(data: [AdminNotificationResource::collection($notifications)]);
    }



    public function destroy($notificationId)
    {
        $this->authorize('Delete-Notification');
        $notification = request()->admin->notifications()->find($notificationId);

        if (!$notification) {
            return $this->responseNotFound('notification not found');
        }
        $notification->delete();

        return $this->responseSuccess('notification deleted');
    }
}
