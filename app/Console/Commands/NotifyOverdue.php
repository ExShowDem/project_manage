<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;

class NotifyOverdue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify overdue tasks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tasks = DB::table('tasks')
            ->whereDate('due_date', '<', Carbon::now())
            ->where('overdue_notified', 0)
            ->get();

        $monitorers = User::with(['roles'])->whereIn('id', function ($subQuery) {
            $subQuery->select('model_has_roles.model_id')
                ->from('model_has_roles')
                ->where('role_id', 9);
        })
        ->pluck('id')
        ->toArray();

        foreach ($tasks as $task)
        {
            $taskable  = $task->taskable_type::find($task->taskable_id);
            $toUserIds = getNotifiedUsers($taskable);
            $toUserIds = array_unique(array_merge($toUserIds, $monitorers));

            $notice = [
                'url'             => route('admin.projects.tasks.show', ['projectId' => $task->project_id, 'id' => $task->id]), 
                'body'            => 'Công việc quá hạn: ' . $task->name . ' [' . $task->code . ']',
                'project_id'      => $task->project_id,
                'targetable_id'   => $task->taskable_id,
                'targetable_type' => $task->taskable_type,
            ];

            $notice['content'] = $notice['body'];

            sendNotifications($toUserIds, $notice);

            DB::table('tasks')
                ->where('id', $task->id)
                ->update(['overdue_notified' => 1]);

            echo 'Overdue Notice Sent: ' . $notice['body'];
        } 

        echo 'All Overdue Notice Sent!';
    }
}
