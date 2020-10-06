<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Notification as PushNotification;
use App\Models\User;
use App\Notifications\PushDemo;
use App\Models\Notification;

function getNotifiedUsers($data)
{
    $taskableType = get_class($data);
    $taskableAttr = $data->getAttributes();
    $creator      = $taskableAttr['created_by'] ?? 1;
    $editor       = null !== Auth::user() ? Auth::user()->id : 1;

    $tasksFromUserIds = DB::table('tasks')
        ->where('taskable_type', $taskableType)
        ->where('taskable_id', $taskableAttr['id'])
        ->pluck('from_user')
        ->toArray();

    $tasksToUserIds = DB::table('tasks')
        ->where('taskable_type', $taskableType)
        ->where('taskable_id', $taskableAttr['id'])
        ->pluck('to_user')
        ->toArray();

    $commentsFromUserIds = DB::table('comments')
        ->where('commentable_type', $taskableType)
        ->where('commentable_id', $taskableAttr['id'])
        ->pluck('from_user')
        ->toArray();

    $commentsContents = DB::table('comments')
        ->where('commentable_type', $taskableType)
        ->where('commentable_id', $taskableAttr['id'])
        ->pluck('content')
        ->toArray();

    $mentionedUserIds = [];

    foreach ($commentsContents as $commentsContent)
    {
        $regex = '/\/projects\/\d+\/users\/(?<userId>\d+)\/edit/';
        preg_match_all($regex, $commentsContent, $matches);

        foreach ($matches["userId"] as $mentionedUserId)
        {
            $mentionedUserIds[] = $mentionedUserId;
        }
    }

    // $files = DB::table('attach_files')
    //     ->where('fileable_type', $taskableType)
    //     ->where('fileable_id', $taskableAttr['id'])
    //     ->get()
    //     ->all();

    $toUserIds = array_unique(array_merge([$editor], [$creator], $tasksFromUserIds, $tasksToUserIds, $commentsFromUserIds, $mentionedUserIds));

    return $toUserIds;
}

function sendNotifications($toUserIds, $notice)
{
    foreach ($toUserIds as $toUserId)
    {
        PushNotification::send(
            User::find($toUserId),
            new PushDemo($notice)
        );

        $notice['to_user']   = $toUserId;
        $notice['from_user'] = null !== Auth::user() ? Auth::user()->id : 1;

        $notification = Notification::create($notice);
        $notification->save();
    }
}
