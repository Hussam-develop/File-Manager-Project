<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewActionNotification extends Notification
{
    use Queueable;
    public $userName;
    public $userAction;
    public $groupId;
    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->userName = $data['userName'];
        $this->userAction = $data['userAction'];
        $this->groupId = $data['groupId'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }
    public function toDatabase($notifiable)
    {
        return [
            'userName' => $this->userName,
            'userAction' => $this->userAction,
            'groupId' => $this->groupId
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'userName' => $this->userName,
            'userAction' => $this->userAction,
            'groupId' => $this->groupId
        ];
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
