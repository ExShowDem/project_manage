<?php
namespace App\Services\Api;

use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use App\Services\BaseService;

class NotificationService extends BaseService
{
    public function __construct(Notification $notification)
    {
        $this->model = $notification;
    }

    public function getMyNotifications($params)
    {
        $query = $this->model->where('to_user', auth()->id())
            ->where('project_id', $params['project_id']);

        return $query->with('targetable')->orderByDesc('created_at');
    }

    public function getUnreadCount($params)
    {
        return $this->getMyNotifications($params)->where('is_read', false)->count();
    }
    public function getUser()
    {
        $users = DB::table('users')->where('id', auth()->id())->get();
        return $users;

    }
    public function UpdateNotifications($project_id,$user_id)
    {
        $affected = DB::table('notifications')
            ->where('to_user', $user_id)
            ->where('project_id', $project_id)
            ->update(['is_read' => 1]);
        //return $users;

    }
}