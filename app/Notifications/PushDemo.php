<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PushDemo extends Notification
{
    use Queueable;

    protected $url;
    protected $title;
    protected $body;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notice)
    {
        $this->url   = isset($notice['url']) ? $notice['url'] : route('admin.projects.index');
        $this->title = isset($notice['title']) ? $notice['title'] : 'Thông báo từ Megaon';
        $this->body  = isset($notice['body']) ? $notice['body'] : '[Demo] - Notification Megaon';
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        $data = [
            'entry_url' => $this->url,
            'base_url'  => url('/'),
        ];

        return (new WebPushMessage)
            ->title($this->title)
            ->icon(url('/notification-icon.png')) // @todo currently this image dont exist. Add it later somewhere in public/images/...
            ->body($this->body)
            ->data($data)
            ->action('Xem', 'view');
        }
}
