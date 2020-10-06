<?php
namespace App\Http\Controllers\Api;


use App\Http\Resources\NotificationResource;
use App\Services\Api\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends BaseController
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $params = $request->only('per_page', 'search_option', 'project_id');

        $notifications = NotificationResource::apiPaginate($this->notificationService->getMyNotifications($params), $request);
        $notifications->unread_count = $this->notificationService->getUnreadCount($params);
        return $this->responseSuccess($notifications);
    }
    public function update(Request $request)
    {
        $params = $request->only('project_id');
        //echo "<pre>";print_r(auth()->user()->id);exit;
        $this->notificationService->UpdateNotifications($params['project_id'],auth()->user()->id);
        //return $this->responseSuccess($notifications);
    }
}