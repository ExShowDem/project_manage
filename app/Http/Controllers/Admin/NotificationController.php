<?php
namespace App\Http\Controllers\Admin;


use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends BaseController
{
    public function read(Request $request, $projectId, $id)
    {
        $notification = Notification::find($id);

        if ($notification->to_user != auth()->id()) {
            return redirect(route('admin.projects.dashboard', $projectId));
        }

        $notification->is_read = true;
        $notification->save();

        return redirect($notification->url);
    }
}