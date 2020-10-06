<?php

namespace App\Listeners;

use App\Events\ActionLogEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\ActionLog;

class ActionLogListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ActionLogEvent  $event
     * @return void
     */
    public function handle(ActionLogEvent $event)
    {
        $user                   = auth()->user();
        $entry                  = new ActionLog;
        $entry->user_id         = isset($user->id) ? $user->id : 1;
        $entry->user_name       = isset($user->name) ? $user->name : 'Admin';
        $entry->model           = $event->model;
        $entry->table_name      = $event->table;
        $entry->method          = $event->method;
        $entry->record_key_name = $event->recordKeyName;
        $entry->record_id       = $event->recordId;
        $entry->params          = serialize($event->parameters);

        $entry->save();
    }
}
