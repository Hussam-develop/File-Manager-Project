<?php

namespace App\Listeners;

use App\Events\NewActionEvent;
use App\Models\Group;
use App\Models\User;
use App\Notifications\NewActionNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NewActionListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewActionEvent $event): void
    {
        // Get all users in the group
         $users = Group::find($event->groupId)->users;
        $data = [
            'userName' => $event->userName,
            'userAction' => $event->userAction,
            'groupId' => $event->groupId
        ];
        foreach ($users as $user) {
            $user->notify(new NewActionNotification($data));
        }
    }
}
